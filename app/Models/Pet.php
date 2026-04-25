<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
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

}
