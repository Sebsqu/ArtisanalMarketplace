<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Products extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'name', 'description', 'price', 'stock_quantity', 'weight', 'dimensions', 'is_active', 'category_id', 'user_id', 'urlImages'
    ];
    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites', 'product_id', 'user_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function productRate()
    {
        return $this->hasMany(ProductRate::class, 'rated_product_id');
    }
}
