<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetGreet extends Model
{
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
    public function volunteer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'volunteer_id');
    }
}
