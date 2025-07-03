<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\AdminRepositoryInterface;

class AdminController extends Controller
{
    public function __construct(
        AdminRepositoryInterface $adminRepository
    ) {
        $this->adminRepository = $adminRepository;
    }
    public function adminDashboard()
    {   
        return view('admin.dashboard', [
            'products' => $this->adminRepository->getAllProducts(20),
            'categories' => $this->adminRepository->getAllCategories()
        ]);
    }
    
    public function adminEditProduct($id)
    {
        return view('admin.editProduct', [
            'product' => $this->adminRepository->editProduct($id),
            'categories' => $this->adminRepository->getAllCategories()
        ]);
    }

    public function adminSaveEditProduct(Request $request, $id)
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

        $product = $this->adminRepository->saveEditedProduct($id, $data);

        return redirect()->route('adminEditProduct', $product->id)->with('status', 'Dane ogłoszenia zostały zaktualizowane.');
    }

    public function adminUsers(Request $request)
    {
        $filters = [
            'name'             => $request->query('name'),
            'email'            => $request->query('email'),
            'city'             => $request->query('city'),
            'phone_number'     => $request->query('phone_number'),
            'is_active'        => $request->query('is_active'),
        ];

        return view('admin.users', [
            'users' => $this->adminRepository->getAllUsers(25, $filters)
        ]);
    }

    public function adminEditUser($id)
    {
        return view('admin.editUser', [
            'user' => $this->adminRepository->adminEditUser($id)
        ]);
    }

    public function adminSaveEditUser(Request $request, int $id)
    {
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
            'is_active' => $request->has('is_active') ? 1 : 0,
            'avatar' => $request->file('avatar')
        ];

        $user = $this->adminRepository->saveEditedUser($id, $data);


        return redirect()->route('adminEditUser', $user->id)
                        ->with('status', 'Dane konta zostały zaktualizowane.');
    }
    
    public function adminOrders()
    {
        return view('admin.orders', [
            'orders' => $this->adminRepository->getOrders(),
        ]);
    }
}
