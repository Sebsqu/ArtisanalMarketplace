<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;

class Favorites extends Model
{
    protected $table = 'favorites';
    protected $fillable = ['user_id', 'product_id'];
}
