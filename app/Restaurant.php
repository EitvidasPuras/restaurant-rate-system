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
}
