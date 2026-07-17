<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReadingTrack;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ReadingTrackController extends Controller
{
    public function index(): View
    {
        return view('admin.reading_tracks.index', [
            'items' => ReadingTrack::query()->orderBy('id')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.reading_tracks.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['required', 'integer'],
            'reflection_id' => ['nullable', 'integer'],
            'read_at' => ['required', 'date'],
            'duration_seconds' => ['nullable', 'integer'],
            'completed' => ['nullable', 'boolean'],
        ]);

        $data['completed'] = $request->boolean('completed');

        ReadingTrack::create($data);

        return redirect()->route('admin.reading-tracks.index')->with('status', 'Reading track created');
    }

    public function edit(ReadingTrack $readingTrack): View
    {
        return view('admin.reading_tracks.edit', ['item' => $readingTrack]);
    }

    public function update(Request $request, ReadingTrack $readingTrack)
    {
        $data = $request->validate([
            'student_id' => ['required', 'integer'],
            'reflection_id' => ['nullable', 'integer'],
            'read_at' => ['required', 'date'],
            'duration_seconds' => ['nullable', 'integer'],
            'completed' => ['nullable', 'boolean'],
        ]);

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

