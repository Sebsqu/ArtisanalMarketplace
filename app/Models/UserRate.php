<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRate extends Model
{
    protected $table = 'user_rate';
    protected $fillable = [
        'user_id',
        'rated_user_id',
        'rate',
        'comment',
        'ip_address',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
