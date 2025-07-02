<?php
namespace App\Repository;

use App\Models\Products\Products;
use App\Models\Products\ProductRate;
use App\Models\Products\Category;
use App\Models\Products\Favorites;
use App\Models\User;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAllProducts(array $filters = [], int $perPage = 10)
    {
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

        return $query->where('is_active', 1)->paginate($perPage);
    }

    public function getAllFavorites(int $id)
    {
        $user = User::findOrFail($id);
        if(!$user) {
            return [];
        }
        return $user->favoriteProducts->pluck('id')->toArray();
    }

    public function getAllCategories()
    {
        return Category::all();
    }

    public function addProduct(array $data)
    {
        return Products::create($data);
    }

    public function showProduct(int $id)
    {
        return Products::findOrFail($id);
    }

    public function incrementViews(int $id)
    {
        return Products::where('id', $id)->increment('views_count');
    }

    public function productRates(int $id)
    {
        return ProductRate::with('user')->where('rated_product_id', $id)->limit(10)->get();
    }

    public function addToFavorite(int $id, int $userId)
    {
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
    }

    public function rateProduct(array $data)
    {
        $userRate = new ProductRate();
        $userRate->user_id = $data['user_id'];
        $userRate->rated_product_id = $data['rated_product_id'];
        $userRate->rate = $data['rate'];
        $userRate->comment = $data['comment'] ?? null;
        $userRate->ip_address = $data['ip_address'] ?? null;
        $userRate->save();
    }

    public function findProduct(int $id)
    {
        return Products::find($id);
    }

    public function findUser(int $id)
    {
        return User::find($id);
    }
}