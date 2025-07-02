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
use App\Repository\UserRepositoryInterface;

class UserController extends Controller
{
    public function __construct(
        UserRepositoryInterface $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    public function dashboard()
    {
        return view('dashboard.index', [
            'products' => $this->userRepository->getUserProducts(session('user_id'))
        ]);
    }

    public function editProduct($id)
    {
        $userProductIds = $this->userRepository->getUserProductIds(session('user_id'));
        if(in_array($id, $userProductIds)){
            return view('products.edit', [
                'product' => $this->userRepository->editProduct($id), 
                'categories' => $this->userRepository->getAllCategories()
            ]);
        } else {
            return redirect()->route('products');
        }
    }

    public function saveEditProduct(Request $request, $id)
    {
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'stock_quantity' => $request->stock_quantity,
            'weight' => $request->weight,
            'dimensions' => $request->dimensions,
            'is_active' => $request->has('is_active'),
            'user_id' => session('user_id'),
            'images' => $request->file('images')
        ];

        $product = $this->userRepository->saveEditedProduct($id, $data);

        return redirect()->route('editProduct', $product->id)->with('status', 'Dane ogłoszenia zostały zaktualizowane.');
    }

    public function userSettingsForm(int $id)
    {
        if ((int) session('user_id') !== $id) {
            return redirect('/');
        }

        return view('dashboard.userSettingsForm', [
            'user' => $this->userRepository->getUser(session('user_id'))
        ]);
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

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'phone_number' => $request->phone_number,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'avatar' => $request->file('avatar')
        ];

        $user = $this->userRepository->updateUser($id, $data);


        return redirect()->route('userSettingsForm', $user->id)
                        ->with('status', 'Dane konta zostały zaktualizowane.');
    }

    public function favorites()
    {
        return view('dashboard.favorites', [
            'favorites' => $this->userRepository->getFavorites(session('user_id'))
        ]);
    }

    public function showUser($id)
    {
        return view('users.show', [
            'user' => $this->userRepository->getUser($id), 
            'products' => $this->userRepository->getUserActiveProducts($id), 
            'rates' => $this->userRepository->getUserRates($id)
        ]);
    }

    public function rateUser(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $data = [
            'user_id' => session('user_id'),
            'rated_user_id' => $id,
            'rate' => $request->input('rating'),
            'comment' => $request->input('comment'),
            'ip' => $request->ip()
        ];

        $this->userRepository->rateUser($data);

        return redirect()->route('showUser', $id)->with('status', 'Ocena została wystawiona.');
    }

    public function ordersHistory()
    {
        return view('dashboard.ordersHistory', [
            'orders' => $this->userRepository->getOrdersHistory(session('user_id'))
        ]);
    }
}