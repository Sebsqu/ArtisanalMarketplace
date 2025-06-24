<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Products\Products;
use App\Models\Products\ProductRate;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verification_token',
        'phone_number',
        'city',
        'postal_code',
        'address',
        'is_active',
        'imageUrl',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function favoriteProducts()
    {
        return $this->belongsToMany(Products::class, 'favorites', 'user_id', 'product_id');
    }
    public function products()
    {
        return $this->hasMany(Products::class, 'user_id');
    }
    public function userRates()
    {
        return $this->hasMany(UserRate::class, 'rated_user_id');
    }
    public function productRates()
    {
        return $this->hasMany(ProductRate::class, 'rated_product_id');
    }
}
