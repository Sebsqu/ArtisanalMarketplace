<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Products\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $data['categories'] = Category::all();
        return view('app.home', $data);
    }
}
