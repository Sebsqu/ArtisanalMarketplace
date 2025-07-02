<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Products\Category;
use Illuminate\Http\Request;
use App\Repository\ProductRepositoryInterface;

class HomeController extends Controller
{
    public function __construct(
        ProductRepositoryInterface $productRepository
    ) {
        $this->productRepository = $productRepository;
    }
    public function index(){
        return view('app.home', [
            'categories' => $this->productRepository->getAllCategories()
        ]);
    }
}
