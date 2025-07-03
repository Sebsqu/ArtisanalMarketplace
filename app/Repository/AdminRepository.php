<?php
namespace App\Repository;

use App\Models\Products\Products;
use App\Models\Products\Category;
use App\Models\User;
use App\Models\Order;

class AdminRepository implements AdminRepositoryInterface
{
    public function getAllProducts(int $perPage)
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

        return $query->paginate($perPage);
    }

    public function getAllCategories()
    {
        return Category::all();
    }

    public function editProduct(int $id)
    {
        return Products::find($id);
    }

    public function saveEditedProduct(int $id, array $data)
    {
        $product = Products::findOrFail($id);
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

    public function getAllUsers(int $perPage, array $filters = [])
    {
        $query = User::query();

        if (!empty($filters['name'])) {
        $query->where('name', 'like', '%' . $filters['name'] . '%');
        }
        if (!empty($filters['email'])) {
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }
        if (!empty($filters['city'])) {
            $query->where('city', 'like', '%' . $filters['city'] . '%');
        }
        if (!empty($filters['phone_number'])) {
            $query->where('phone_number', 'like', '%' . $filters['phone_number'] . '%');
        }
        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->paginate($perPage);
    }

    public function adminEditUser(int $id)
    {
        return User::find($id);
    }

    public function saveEditedUser(int $id, array $data)
    {
        $user = User::findOrFail($id);
        $user->name = $data['name'];
        $user->email = $data['email'];

        if ($data['password']) {
            $user->password = bcrypt($data['password']);
        }

        $user->phone_number = $data['phone_number'];
        $user->city = $data['city'];
        $user->postal_code = $data['postal_code'];
        $user->address = $data['address'];
        $user->is_active = $data['is_active'];
        if ($data['avatar']) {
            $file = $data['avatar'];
            $filename = $user->id . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $path = $file->storeAs('users', $filename, 'public');
            $user->imageUrl = $path;
        }
        $user->save();
        return $user;
    }

    public function getOrders()
    {
        return Order::with('OrderItems')->get();
    }
}