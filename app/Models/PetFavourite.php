<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetFavourite extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'pet_id'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
}
