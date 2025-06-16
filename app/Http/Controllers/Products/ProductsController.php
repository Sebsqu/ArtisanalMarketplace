<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\Products\Category;
use App\Models\Products\Products;
use Illuminate\Http\Request;

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

        $categories = Category::all();

        return view('products.index', [
            'categories' => $categories,
            'products'   => $products,
        ]);
    }    
}
