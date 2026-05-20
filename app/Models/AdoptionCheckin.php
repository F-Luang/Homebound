<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdoptionCheckin extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'application_id', 'label', 'due_at',
        'status', 'notes', 'completed_by', 'completed_at',
    ];

    protected $casts = [
        'due_at'       => 'date',
        'completed_at' => 'datetime',
        'created_at'   => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function isOverdue(): bool
    {
        return $this->status === 'pending' && $this->due_at->isPast();
    }
}
