<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surrender extends Model
{
    protected $fillable = [
        'submitter_name', 'submitter_email', 'submitter_phone',
        'pet_name', 'species', 'breed', 'age_years',
        'urgency', 'reason', 'health_notes', 'behavioral_notes',
        'status', 'admin_notes',
    ];

    public function urgencyLabel(): string
    {
        return match($this->urgency) {
            'high'   => '🔴 High — needs placement within days',
            'medium' => '🟡 Medium — within a few weeks',
            'low'    => '🟢 Low — flexible timeline',
            default  => $this->urgency,
        };
    }
}
