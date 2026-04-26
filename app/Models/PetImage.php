<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PetImage extends Model
{
    public $timestamps = false;

    protected $fillable = ['pet_id', 'path', 'is_primary'];

    protected $casts = ['is_primary' => 'boolean'];

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }
}