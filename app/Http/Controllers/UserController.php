<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products\Products;
use App\Models\Products\Category;
use App\Models\Products\Favorites;
use App\Models\Order;
use App\Models\User;
use App\Models\UserRate;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function dashboard()
    {
        $products = Products::where('user_id', auth()->user()->id)->get();
        return view('dashboard.index', ['products' => $products]);
    }

    public function editProduct($id)
    {
        $product = Products::findOrFail($id);
        $categories = Category::all();
        return view('products.edit', ['product' => $product, 'categories' => $categories]);
    }

    public function saveEditProduct(Request $request, $id)
    {
        $product = Products::findOrFail($id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->stock_quantity = $request->stock_quantity;
        $product->weight = $request->weight;
        $product->dimensions = $request->dimensions;
        $product->is_active = $request->has('is_active');

        $imagePaths = [];

        if ($request->hasFile('images')) {
            if ($product->urlImages) {
                $oldImages = explode(',', $product->urlImages);
                foreach ($oldImages as $oldImage) {
                    $filePath = str_replace('storage/', '', $oldImage);
                    if (Storage::disk('public')->exists($filePath)) {
                        Storage::disk('public')->delete($filePath);
                    }
                }
            }

            $userId = auth()->id();

            foreach ($request->file('images') as $image) {
                $filename = $product->id . '_' . $userId . '_' . time() . '_' . preg_replace('/\s+/', '_', $image->getClientOriginalName());
                $path = $image->storeAs('products', $filename, 'public');
                $imagePaths[] = 'storage/' . $path;
            }

            $product->urlImages = implode(',', $imagePaths);
        }

        $product->save();
        return redirect()->route('editProduct', $product->id)->with('status', 'Dane ogłoszenia zostały zaktualizowane.');
    }

    public function userSettingsForm(int $id)
    {
        if ((int) session('user_id') !== $id) {
            return redirect('/');
        }

        $user = User::findOrFail($id);
        return view('dashboard.userSettingsForm', ['user' => $user]);
    }

    public function saveUser(Request $request, int $id)
    {
        if ((int) session('user_id') !== $id) {
            return redirect('/');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:10|confirmed',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->phone_number = $request->phone_number;
        $user->city = $request->city;
        $user->postal_code = $request->postal_code;
        $user->address = $request->address;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = $user->id . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $path = $file->storeAs('users', $filename, 'public');
            $user->imageUrl = $path;
        }
        $user->save();

        return redirect()->route('userSettingsForm', $user->id)
                        ->with('status', 'Dane konta zostały zaktualizowane.');
    }

    public function favorites()
    {
        $favorites = User::find(session('user_id'))->favoriteProducts()->where('is_active', 1)->get();
        return view('dashboard.favorites', ['favorites' => $favorites]);
    }

    public function showUser($id)
    {
        $user = User::findOrFail($id);
        $products = Products::where('user_id', $id)->where('is_active', 1)->get();
        $rates = UserRate::where('rated_user_id', $id)->limit(10)->get();
        return view('users.show', ['user' => $user, 'products' => $products, 'rates' => $rates]);
    }

    public function rateUser(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $userRate = new UserRate();
        $userRate->user_id = session('user_id');
        $userRate->rated_user_id = $id;
        $userRate->rate = $request->input('rating');
        $userRate->comment = $request->input('comment');
        $userRate->ip_address = $request->ip();
        $userRate->save();

        return redirect()->route('showUser', $id)->with('status', 'Ocena została wystawiona.');
    }

    public function ordersHistory()
    {
        $orders = Order::with('orderItems')->where('user_id', session('user_id'))->orderBy('created_at', 'desc')->get();
        return view('dashboard.ordersHistory', ['orders' => $orders]);
    }
}