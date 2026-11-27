<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'title',
        'description',
        'category',
        'starting_price',
        'current_price',
        'condition',
        'user_id',
    ];
}
