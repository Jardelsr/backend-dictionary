<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class History extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'histories';

    protected $fillable = ['user_id', 'word'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
