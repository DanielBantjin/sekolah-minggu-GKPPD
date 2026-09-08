<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReadingTrack extends Model
{
    protected $fillable = [
        'student_id',
        'reflection_id',
        'read_at',
        'duration_seconds',
        'completed',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'duration_seconds' => 'integer',
        'completed' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function reflection()
    {
        return $this->belongsTo(Reflection::class);
    }
}

