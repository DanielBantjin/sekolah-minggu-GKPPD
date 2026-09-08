<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Reflection;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReflectionController
{
    public function index(): View
    {
        $reflections = Reflection::where('created_by', auth()->id())
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('teacher.reflections.index', compact('reflections'));
    }

    public function create(): View
    {
        return view('teacher.reflections.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'bible_verse' => 'nullable|string|max:255',
            'date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['created_by'] = auth()->id();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('reflections', 'public');
            $validated['image'] = $path;
        }

        Reflection::create($validated);

        return redirect()->route('teacher.reflections.index')->with('success', 'Renungan berhasil ditambahkan!');
    }

    public function edit(Reflection $reflection): View
    {
        $this->ensureOwnership($reflection);

        return view('teacher.reflections.edit', compact('reflection'));
    }

    public function update(Request $request, Reflection $reflection): RedirectResponse
    {
        $this->ensureOwnership($reflection);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'bible_verse' => 'nullable|string|max:255',
            'date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($reflection->image) {
                Storage::disk('public')->delete($reflection->image);
            }

            $validated['image'] = $request->file('image')->store('reflections', 'public');
        }

        $reflection->update($validated);

        return redirect()->route('teacher.reflections.index')->with('success', 'Renungan berhasil diperbarui!');
    }

    public function destroy(Reflection $reflection): RedirectResponse
    {
        $this->ensureOwnership($reflection);

        if ($reflection->image) {
            Storage::disk('public')->delete($reflection->image);
        }

        $reflection->delete();

        return redirect()->route('teacher.reflections.index')->with('success', 'Renungan berhasil dihapus!');
    }

    private function ensureOwnership(Reflection $reflection): void
    {
        if ($reflection->created_by !== auth()->id()) {
            abort(403, 'Anda tidak berwenang mengelola renungan ini.');
        }
    }
}
