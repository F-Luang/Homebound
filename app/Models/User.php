<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;  // ADD THIS
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_approved',  // ADD THIS — needed for mass assignment from seeders/factories
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_approved' => 'boolean',  // ADD THIS
        ];
    }
    public function isPendingApproval(): bool
    {
        return $this->role === 'volunteer' && !$this->is_approved;
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class, 'recorded_by');
    }

    public function meetGreets(): HasMany
    {
        return $this->hasMany(MeetGreet::class, 'volunteer_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isVolunteer(): bool
    {
        return $this->role === 'volunteer';
    }
}