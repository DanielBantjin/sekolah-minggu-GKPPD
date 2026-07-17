<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(): View
    {
        return view('admin.students.index', [
            'students' => Student::query()->orderBy('id')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.students.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'class' => ['nullable', 'string', 'max:255'],
            'parent_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
        ]);

        Student::create($data);

        return redirect()->route('admin.students.index')->with('status', 'Student created');
    }

    public function edit(Student $student): View
    {
        return view('admin.students.edit', ['student' => $student]);
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'class' => ['nullable', 'string', 'max:255'],
            'parent_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
        ]);

        $student->update($data);

        return redirect()->route('admin.students.index')->with('status', 'Student updated');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('admin.students.index')->with('status', 'Student deleted');
    }
}

