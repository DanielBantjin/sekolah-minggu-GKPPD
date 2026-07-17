<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Reflection;
use App\Models\ReadingTrack;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReadingController extends Controller
{
    public function today(): View
    {
        $student = Student::query()->where('user_id', Auth::id())->firstOrFail();

        $today = Carbon::today();

        $reflection = Reflection::query()
            ->where('is_active', true)
            ->whereDate('date', $today)
            ->first();

        if (!$reflection) {
            $reflection = Reflection::query()
                ->where('is_active', true)
                ->where('date', '<=', $today->toDateString())
                ->orderBy('date', 'desc')
                ->first();
        }

        if (!$reflection) {
            abort(404, 'Renungan tidak tersedia');
        }

        $todayKey = $today->toDateString();

        $existing = ReadingTrack::query()
            ->where('student_id', $student->id)
            ->where('reflection_id', $reflection->id)
            ->whereDate('read_at', $todayKey)
            ->first();

        return view('student.reading.today', [
            'reflection' => $reflection,
            'existing' => $existing,
        ]);
    }

    public function store(Request $request): 
        
        \Illuminate\Http\RedirectResponse
    {
        $student = Student::query()->where('user_id', Auth::id())->firstOrFail();

        $data = $request->validate([
            'reflection_id' => ['required', 'integer', 'exists:reflections,id'],
            'read_at' => ['required', 'date'],
            'duration_seconds' => ['required', 'integer', 'min:0'],
            'completed' => ['nullable', 'boolean'],
        ]);

        $completed = $request->boolean('completed');

        $reading = ReadingTrack::query()->updateOrCreate(
            [
                'student_id' => $student->id,
                'reflection_id' => $data['reflection_id'],
                'read_at' => $data['read_at'],
            ],
            [
                'duration_seconds' => $data['duration_seconds'],
                'completed' => $completed,
            ]
        );

        return redirect()->route('student.reading.today')->with('status', 'Pembacaan tersimpan.');
    }
}

