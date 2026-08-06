<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReadingTrack;
use App\Models\Reflection;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ReadingTrackController extends Controller
{
    public function index(): View
    {
        return view('admin.reading-tracks.index', [
            'items' => ReadingTrack::query()->orderBy('id')->paginate(20),
        ]);
    }

    public function create(): View
    {
        $students = Student::query()->with('user')->orderBy('id')->get();
        $reflections = Reflection::query()->orderBy('date')->get();

        return view('admin.reading-tracks.create', compact('students', 'reflections'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'reflection_id' => ['nullable', 'integer', 'exists:reflections,id'],
            'read_at' => ['required', 'date'],
            'duration_seconds' => ['nullable', 'integer'],
            'completed' => ['nullable', 'boolean'],
        ]);

        if (empty($data['reflection_id'])) {
            $data['reflection_id'] = Reflection::query()->value('id') ?? 1;
        }

        $data['duration_seconds'] = (int) ($data['duration_seconds'] ?? 0);
        $data['completed'] = $request->boolean('completed');

        ReadingTrack::create($data);

        return redirect()->route('admin.reading-tracks.index')->with('status', 'Reading track created');
    }

    public function edit(ReadingTrack $readingTrack): View
    {
        $students = Student::query()->with('user')->orderBy('id')->get();
        $reflections = Reflection::query()->orderBy('date')->get();

        return view('admin.reading-tracks.edit', compact('readingTrack', 'students', 'reflections'));
    }

    public function update(Request $request, ReadingTrack $readingTrack)
    {
        $data = $request->validate([
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'reflection_id' => ['nullable', 'integer', 'exists:reflections,id'],
            'read_at' => ['required', 'date'],
            'duration_seconds' => ['nullable', 'integer'],
            'completed' => ['nullable', 'boolean'],
        ]);

        if (empty($data['reflection_id'])) {
            $data['reflection_id'] = Reflection::query()->value('id') ?? 1;
        }

        $data['duration_seconds'] = (int) ($data['duration_seconds'] ?? 0);
        $data['completed'] = $request->boolean('completed');

        $readingTrack->update($data);

        return redirect()->route('admin.reading-tracks.index')->with('status', 'Reading track updated');
    }

    public function destroy(ReadingTrack $readingTrack)
    {
        $readingTrack->delete();
        return redirect()->route('admin.reading-tracks.index')->with('status', 'Reading track deleted');
    }
}

