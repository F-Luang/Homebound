<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalRecord extends Model
{
    public $timestamps = false; // only has created_at per your migration

    protected $fillable = [
        'pet_id',
        'recorded_by',
        'record_type',
        'record_date',
        'notes',
    ];

    protected $casts = [
        'record_date' => 'date',
        'created_at' => 'datetime',
    ];

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}