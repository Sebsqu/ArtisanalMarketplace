<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ProductRate extends Model
{
    protected $table = 'product_rate';
    protected $fillable = [
        'user_id',
        'rated_product_id',
        'rate',
        'comment',
        'ip_address',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
