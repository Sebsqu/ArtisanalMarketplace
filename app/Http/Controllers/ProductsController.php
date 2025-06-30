<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\Products\Category;
use App\Models\Products\Favorites;
use App\Models\Products\Products;
use App\Models\Products\ProductRate;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'category'   => $request->query('category'),
            'priceFrom'  => $request->query('priceFrom'),
            'priceTo'    => $request->query('priceTo'),
            'search'     => $request->query('search'),
        ];

        $page = $request->query('page', 1);
        $cacheKey = 'products_' . md5(json_encode($filters)) . '_page_' . $page;

        $products = Cache::remember($cacheKey, 60, function () use ($filters) {
            $query = Products::query();

            if (!empty($filters['category'])) {
                $query->where('category_id', $filters['category']);
            }
            if (!empty($filters['priceFrom'])) {
                $query->where('price', '>=', $filters['priceFrom']);
            }
            if (!empty($filters['priceTo'])) {
                $query->where('price', '<=', $filters['priceTo']);
            }
            if (!empty($filters['search'])) {
                $search = $filters['search'];
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            return $query->where('is_active', 1)->paginate(6);
        });
        $favoritedProductIds = auth()->check() ? auth()->user()->favoriteProducts->pluck('id')->toArray() : [];
        $categories = Category::all();

        return view('products.index', [
            'categories' => $categories,
            'products'   => $products,
            'favoritedProductIds' => $favoritedProductIds,
        ]);
    }    

    public function addProductForm(){
        $categories = Category::all();
        return view('products.addNew', ['categories' => $categories]);
    }

    public function addProduct(Request $request)
    {
        $userId = $request->user()->id;

        $product = Products::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'weight' => $request->weight,
            'dimensions' => $request->dimensions,
            'is_active' => $request->has('is_active'),
            'category_id' => $request->category_id,
            'user_id' => $userId,
            'urlImages' => '',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = $product->id . '_' . $userId . '_' . time() . '_' . preg_replace('/\s+/', '_', $image->getClientOriginalName());
                $path = $image->storeAs('products', $filename, 'public');
                $imagePaths[] = 'storage/' . $path; 
            }
        }

        $product->urlImages = implode(',', $imagePaths);
        $product->save();

        return redirect('/')->with('status', 'Produkt dodany!');
    }

    public function showProduct($id){
        $product = Products::findOrFail($id);
        $product->views_count++;
        $product->save();
        $productRates = ProductRate::with('user')->where('rated_product_id', $id)->limit(10)->get();
        return view('products.showProduct', ['product' => $product, 'productRates' => $productRates]);
    }

    public function addToFavorite($id)
    {
        $userId = auth()->id();

        $fav = Favorites::where('user_id', $userId)
                        ->where('product_id', $id)
                        ->first();

        if ($fav) {
            $fav->delete();
        } else {
            Favorites::create([
                'user_id' => $userId,
                'product_id' => $id,
            ]);
        }

        return redirect()->back();
    }

    public function rateProduct(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $userRate = new ProductRate();
        $userRate->user_id = session('user_id');
        $userRate->rated_product_id = $id;
        $userRate->rate = $request->input('rating');
        $userRate->comment = $request->input('comment');
        $userRate->ip_address = $request->ip();
        $userRate->save();

        return redirect()->route('showProduct', $id)->with('status', 'Ocena została wystawiona.');
    }

    public function addToCart($id)
    {
        $cart = session()->get('cart', []);
        $product = Products::find($id);
        if (isset($cart[$id])) {
            if ($cart[$id]['quantity'] < $product->stock_quantity) {
                $cart[$id]['quantity']++;
            }
        } else {
            $cart[$id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'urlImages' => $product->urlImages,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back();
    }

    public function cart()
    {
        $items = session('cart', []);
        return view('cart.cart', ['items' => $items]);
    }

    public function removeItem($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            if ($cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity']--;
            } else {
                unset($cart[$id]);
            }
            session()->put('cart', $cart);
        }
        return redirect()->back();
    }

    public function checkout()
    {
        $user = User::find(session('user_id'));
        $items = session('cart', []);
        return view('cart.checkout', ['items' => $items, 'user' => $user]);
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);

        $userId = session('user_id');
        $cart = session('cart', []);
        $totalPrice = array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));
        $totalItems = array_sum(array_column($cart, 'quantity'));
    
        $newOrder = new Order();
        $newOrder->user_id = $userId;
        $newOrder->name = $request->name;
        $newOrder->city = $request->city;
        $newOrder->address = $request->address;
        $newOrder->postal_code = $request->postal_code;
        $newOrder->phone_number = $request->phone_number;
        $newOrder->email = $request->email;
        $newOrder->total_price = $totalPrice;
        $newOrder->total_items = $totalItems;
        $newOrder->save();

        foreach($cart as $item){
            $product = Products::find($item['id']);
            $newOrderItem = new OrderItem();
            $newOrderItem->order_id = $newOrder->id;
            $newOrderItem->product_id = $item['id'];
            $newOrderItem->product_name = $item['name'];
            $newOrderItem->product_description = $product->description;
            $newOrderItem->price = $item['price'];
            $newOrderItem->quantity = $item['quantity'];
            $newOrderItem->weight = $product->weight;
            $newOrderItem->total_price = $item['price'] * $item['quantity'];
            $newOrderItem->dimensions = $product->dimensions;
            $newOrderItem->image_url = $product->urlImages;
            $newOrderItem->save();
        }
        
        
        foreach($cart as $item){
            Products::where('id', $item['id'])->decrement('stock_quantity', $item['quantity']);
        }

        $owners = [];
        foreach ($cart as $item) {
            $product = Products::find($item['id']);
            if (!$product || !$product->user) continue;
            $ownerId = $product->user_id;
            if (!isset($owners[$ownerId])) {
                $owners[$ownerId] = [
                    'email' => $product->user->email,
                    'name' => $product->user->name,
                    'items' => [],
                ];
            }
            $owners[$ownerId]['items'][] = $item;
        }

        foreach ($owners as $owner) {
            Mail::raw(
                "Twoje produkty zostały sprzedane w zamówieniu nr {$newOrder->id}:\n" .
                collect($owner['items'])->map(function($item) {
                    return "- {$item['name']} (ilość: {$item['quantity']})";
                })->implode("\n"),
                function($message) use ($owner, $newOrder) {
                    $message->to($owner['email'])
                        ->subject("Sprzedaż Twoich produktów - zamówienie nr {$newOrder->id}");
                }
            );
        }

        Mail::raw(
            "Dziękujemy za złożenie zamówienia nr {$newOrder->id}!\n\n" .
            "Podsumowanie zamówienia:\n" .
            collect($cart)->map(function($item) {
                return "- {$item['name']} (ilość: {$item['quantity']}, cena: {$item['price']} zł)";
            })->implode("\n") .
            "\n\nŁączna kwota: {$newOrder->total_price} zł\n",
            function($message) use ($request, $newOrder) {
                $message->to($request->email)
                    ->subject("Potwierdzenie zamówienia nr {$newOrder->id}");
            }
        );

        session()->forget('cart');

        return redirect('/')->with('status', 'Zamówienie zostało złożone!');
    }

}
