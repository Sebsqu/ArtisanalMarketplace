<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'user_carts';
    protected $fillable = ['user_id', 'cart_items'];
}
