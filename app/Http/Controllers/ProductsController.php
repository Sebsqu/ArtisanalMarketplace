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
use App\Repository\ProductRepositoryInterface;
use App\Repository\OrderRepositoryInterface;
use App\Services\OrderNotificationService;


class ProductsController extends Controller
{
    public function __construct(
        ProductRepositoryInterface $productRepository, 
        OrderRepositoryInterface $orderRepository, 
        OrderNotificationService $orderNotificationService
    ) {
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
        $this->orderNotificationService = $orderNotificationService;
    }

    public function index(Request $request)
    {
        $filters = [
            'category'   => $request->query('category'),
            'priceFrom'  => $request->query('priceFrom'),
            'priceTo'    => $request->query('priceTo'),
            'search'     => $request->query('search'),
        ];
        
        return view('products.index', [
            'products' => $this->productRepository->getAllProducts($filters, 6),
            'categories' => $this->productRepository->getAllCategories(),
            'favoritedProductIds' => $this->productRepository->getAllFavorites(session('user_id')),
        ]);
    }    

    public function addProductForm(){
        return view('products.addNew', [
            'categories' => $this->productRepository->getAllCategories(),
        ]);
    }

    public function addProduct(Request $request)
    {
        $product = $this->productRepository->addProduct([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'weight' => $request->weight,
            'dimensions' => $request->dimensions,
            'is_active' => $request->has('is_active'),
            'category_id' => $request->category_id,
            'user_id' => session('user_id'),
            'urlImages' => '',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = $product->id . '_' . session('user_id') . '_' . time() . '_' . preg_replace('/\s+/', '_', $image->getClientOriginalName());
                $path = $image->storeAs('products', $filename, 'public');
                $imagePaths[] = 'storage/' . $path; 
            }
        }

        $product->urlImages = implode(',', $imagePaths);
        $product->save();

        return redirect('/')->with('status', 'Produkt dodany!');
    }

    public function showProduct($id){
        $this->productRepository->incrementViews($id);

        return view('products.showProduct', [
            'product' => $this->productRepository->showProduct($id), 
            'productRates' => $this->productRepository->productRates($id)
        ]);
    }

    public function addToFavorite($id)
    {
        $this->productRepository->addToFavorite($id, session('user_id'));
        return redirect()->back();
    }

    public function rateProduct(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $this->productRepository->rateProduct([
            'user_id' => session('user_id'),
            'rated_product_id' => $id,
            'rate' => $request->input('rating'),
            'comment' => $request->input('comment'),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('showProduct', $id)->with('status', 'Ocena została wystawiona.');
    }

    public function addToCart($id)
    {
        $cart = session()->get('cart', []);
        $product = $this->productRepository->findProduct($id);

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
        $items = session('cart', []);
        return view('cart.checkout', [
            'items' => $items, 
            'user' => $this->productRepository->findUser(session('user_id')),
        ]);
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

        $totalPrice = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        $totalItems = array_sum(array_column($cart, 'quantity'));

        $orderData = [
            'user_id' => $userId,
            'name' => $request->name,
            'city' => $request->city,
            'address' => $request->address,
            'postal_code' => $request->postal_code,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'total_price' => $totalPrice,
            'total_items' => $totalItems,
        ];
    
        $order = $this->orderRepository->placeOrder($orderData, $cart);
        $this->orderNotificationService->notifyOwners($order);
        $this->orderNotificationService->notifyCustomer($order);

        session()->forget('cart');

        return redirect('/')->with('status', 'Zamówienie zostało złożone!');
    }

}
