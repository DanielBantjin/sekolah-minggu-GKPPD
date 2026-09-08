<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reflection;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ReflectionController extends Controller
{
    public function index(): View
    {
        return view('admin.reflections.index', [
            'reflections' => Reflection::query()->orderBy('id')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.reflections.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'string'],
            'bible_verse' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['created_by'] = $request->user()->id;

        $data['is_active'] = $request->boolean('is_active');

        Reflection::create($data);

        return redirect()->route('admin.reflections.index')->with('status', 'Reflection created');
    }

    public function edit(Reflection $reflection): View
    {
        return view('admin.reflections.edit', ['reflection' => $reflection]);
    }

    public function update(Request $request, Reflection $reflection)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'string'],
            'bible_verse' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $reflection->update($data);

        return redirect()->route('admin.reflections.index')->with('status', 'Reflection updated');
    }

    public function destroy(Reflection $reflection)
    {
        $reflection->delete();
        return redirect()->route('admin.reflections.index')->with('status', 'Reflection deleted');
    }
}

