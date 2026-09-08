<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Reflection;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;

class ReflectionController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today()->toDateString();

        $reflections = Reflection::query()
            ->where('is_active', true)
            ->where('date', '<=', $today)
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('student.reflections.index', compact('reflections'));
    }

    public function show(Reflection $reflection): View
    {
        $today = Carbon::today()->toDateString();

        if ($reflection->is_active !== true || $reflection->date->greaterThan(Carbon::parse($today))) {
            abort(403, 'Renungan ini belum tersedia untuk Anda.');
        }

        return view('student.reflections.show', compact('reflection'));
    }
}
