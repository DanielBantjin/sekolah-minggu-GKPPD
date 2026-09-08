<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'student_id',
        'class',
        'date_of_birth',
        'parent_name',
        'parent_phone',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function readingTracks()
    {
        return $this->hasMany(ReadingTrack::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function finances()
    {
        return $this->hasMany(Finance::class);
    }
}

