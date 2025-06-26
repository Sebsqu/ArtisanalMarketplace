<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_description',
        'price',
        'quantity',
        'weight',
        'total_price',
        'weight',
        'dimensions',
        'image_url',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

}
