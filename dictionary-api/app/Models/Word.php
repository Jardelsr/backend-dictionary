<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Word extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'words';

    protected $fillable = ['user_id', 'word', 'added_at'];

    protected $casts = [
        'added_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
