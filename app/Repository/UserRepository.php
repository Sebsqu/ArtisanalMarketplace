<?php
namespace App\Repository;

use App\Models\Products\Products;
use App\Models\Products\Category;
use App\Models\User;
use App\Models\UserRate;
use App\Models\Order;

class UserRepository implements UserRepositoryInterface
{
    public function getUserProducts(int $userId)
    {
        return Products::where('user_id', $userId)->get();
    }
    public function editProduct(int $productId)
    {
        return Products::find($productId);
    }
    public function getAllCategories()
    {
        return Category::all();
    }
    public function getUserProductIds(int $userId)
    {
        return Products::where('user_id', $userId)->pluck('id')->toArray();
    }
    public function saveEditedProduct(int $productId, array $data)
    {
        $product = Products::findOrFail($productId);
        $product->name = $data['name'];
        $product->description = $data['description'];
        $product->price = $data['price'];
        $product->category_id = $data['category_id'];
        $product->stock_quantity = $data['stock_quantity'];
        $product->weight = $data['weight'];
        $product->dimensions = $data['dimensions'];
        $product->is_active = $data['is_active'];

        if (isset($data['images'])) {
            if ($product->urlImages) {
                $oldImages = explode(',', $product->urlImages);
                foreach ($oldImages as $oldImage) {
                    $filePath = str_replace('storage/', '', $oldImage);
                    if (\Storage::disk('public')->exists($filePath)) {
                        \Storage::disk('public')->delete($filePath);
                    }
                }
            }
            $imagePaths = [];
            foreach ($data['images'] as $image) {
                $filename = $product->id . '_' . $data['user_id'] . '_' . time() . '_' . preg_replace('/\s+/', '_', $image->getClientOriginalName());
                $path = $image->storeAs('products', $filename, 'public');
                $imagePaths[] = 'storage/' . $path;
            }
            $product->urlImages = implode(',', $imagePaths);
        }

        $product->save();
        return $product;
    }

    public function getUser(int $userId)
    {
        return User::find($userId);
    }

    public function updateUser(int $userId, array $data)
    {
        $user = User::findOrFail($userId);
        $user->name = $data['name'];
        $user->email = $data['email'];

        if ($data['password']) {
            $user->password = bcrypt($data['password']);
        }

        $user->phone_number = $data['phone_number'];
        $user->city = $data['city'];
        $user->postal_code = $data['postal_code'];
        $user->address = $data['address'];
        if ($data['avatar']) {
            $file = $data['avatar'];
            $filename = $user->id . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $path = $file->storeAs('users', $filename, 'public');
            $user->imageUrl = $path;
        }
        $user->save();
        return $user;
    }

    public function getFavorites(int $userId)
    {
        return User::find(session('user_id'))->favoriteProducts()->where('is_active', 1)->get();
    }

    public function getUserActiveProducts(int $userId)
    {
        return Products::where('user_id', $userId)->where('is_active', 1)->get();
    }

    public function getUserRates(int $userId)
    {
        return UserRate::where('rated_user_id', $userId)->limit(10)->get();
    }

    public function rateUser(array $data)
    {
        $userRate = new UserRate();
        $userRate->user_id = $data['user_id'];
        $userRate->rated_user_id = $data['rated_user_id'];
        $userRate->rate = $data['rate'];
        $userRate->comment = $data['comment'];
        $userRate->ip_address = $data['ip'];
        $userRate->save();
    }

    public function getOrdersHistory(int $userId)
    {
        return Order::with('orderItems')->where('user_id', $userId)->orderBy('created_at', 'desc')->get();
    }
}