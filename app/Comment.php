<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'restaurant_id',
        'text'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function restaurant()
    {
        return $this->hasOne(Restaurant::class, 'id', 'restaurant_id');
    }
}
