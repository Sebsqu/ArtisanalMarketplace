<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'user_id',
        'name',
        'city',
        'address',
        'postal_code',
        'phone_number',
        'email',
        'total_price',
        'total_items',
        'status',
        'payment_method',
    ];

    public function OrderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
