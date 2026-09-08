<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Reflection;
use App\Models\ReadingTrack;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ReadingReportController extends Controller
{
    public function today(Request $request): View
    {
        $selectedReflectionId = $request->query('reflection_id');

        $reflections = Reflection::query()
            ->where('is_active', true)
            ->orderByDesc('date')
            ->get();

        $reflection = null;

        if ($selectedReflectionId) {
            $reflection = $reflections->firstWhere('id', (int) $selectedReflectionId);
        }

        if (!$reflection) {
            $today = Carbon::today();
            $reflection = Reflection::query()
                ->whereDate('date', $today)
                ->where('is_active', true)
                ->first();
        }

        if (!$reflection) {
            $reflection = $reflections->first();
        }

        if (!$reflection) {
            abort(404, 'Renungan tidak tersedia');
        }

        $tracks = ReadingTrack::query()
            ->where('reflection_id', $reflection->id)
            ->whereDate('read_at', $reflection->date->toDateString())
            ->with('student.user')
            ->orderBy('duration_seconds', 'desc')
            ->paginate(50);

        return view('teacher.reading_report.today', [
            'reflection' => $reflection,
            'reflections' => $reflections,
            'tracks' => $tracks,
        ]);
    }
}

