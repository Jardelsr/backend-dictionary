<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Favorite extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'favorites';

    protected $fillable = ['user_id', 'word'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
