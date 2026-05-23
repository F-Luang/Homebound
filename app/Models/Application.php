<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Application extends Model
{
    const CREATED_AT = 'submitted_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'user_id',
        'pet_id',
        'status',
        'home_type',
        'has_yard',
        'has_other_pets',
        'other_pets_description',
        'has_children',
        'children_ages',
        'experience',
        'hours_alone',
        'reason',
        'fee_paid',
        'payment_method',
        'payment_reference',
        'notes',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at'   => 'datetime',
        'has_yard'       => 'boolean',
        'has_other_pets' => 'boolean',
        'has_children'   => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public function meetGreet(): HasOne
    {
        return $this->hasOne(MeetGreet::class);
    }

    public function successStory(): HasOne
    {
        return $this->hasOne(SuccessStory::class);
    }

    public function checkins()
    {
        return $this->hasMany(AdoptionCheckin::class)->orderBy('due_at');
    }
}