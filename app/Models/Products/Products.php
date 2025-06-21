<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'name', 'description', 'price', 'stock_quantity', 'weight', 'dimensions', 'is_active', 'category_id', 'user_id', 'urlImages'
    ];
}
