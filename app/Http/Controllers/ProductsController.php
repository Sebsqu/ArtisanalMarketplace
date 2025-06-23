<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\Products\Category;
use App\Models\Products\Favorites;
use App\Models\Products\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

        $cacheKey = 'products_' . md5(json_encode($filters));

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

            return $query->where('is_active', 1)->get();
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
        return view('products.showProduct', ['product' => $product]);
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


}
