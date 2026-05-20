<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiaryEntry extends Model
{
    public $timestamps = false;

    protected $fillable = ['pet_id', 'posted_by', 'content', 'image_path'];

    protected $casts = ['created_at' => 'datetime'];

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }
}
