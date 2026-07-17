<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reflection extends Model
{
    protected $fillable = [
        'created_by',
        'title',
        'content',
        'image',
        'date',
        'bible_verse',
        'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function readingTracks()
    {
        return $this->hasMany(ReadingTrack::class);
    }
}

