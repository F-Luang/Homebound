<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pet extends Model
{
    protected $fillable = [
        'name',
        'species',
        'breed',
        'age_months',
        'size',
        'activity_level',
        'good_with_kids',
        'hypoallergenic',
        'is_senior',
        'status',
        'bio',
        'weight_kg',
        'food',
        'feeding_time',
        'water',
        'medications',
        'vet',
        'special_note',

    ];

    protected $casts = [
        'good_with_kids' => 'boolean',
        'hypoallergenic' => 'boolean',
        'is_senior' => 'boolean',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class)->latest('record_date');
    }

    public function images(): HasMany
    {
        return $this->hasMany(PetImage::class);
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(PetImage::class)->where('is_primary', true);
    }

    public function favourites(): HasMany
    {
        return $this->hasMany(PetFavourite::class);
    }

    public function diaryEntries(): HasMany
    {
        return $this->hasMany(DiaryEntry::class)->latest();
    }
}