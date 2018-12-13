<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'seats',
        'type_id',
        'total_count',
        'average_rating'
    ];

    public function type()
    {
        return $this->hasOne(Type::class, 'id', 'type_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'restaurant_id', 'id')
            ->orderBy('updated_at', 'desc');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'restaurant_id', 'id');
    }
}
