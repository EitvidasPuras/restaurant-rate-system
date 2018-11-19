<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'user_id',
        'restaurant_id',
        'rating',
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
