<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(): View
    {
        $items = Attendance::query()
            ->orderByDesc('date')
            ->orderBy('class_label')
            ->paginate(20);

        $weeklySummary = Attendance::query()
            ->selectRaw('YEAR(date) as year, WEEK(date, 1) as week, SUM(present_count) as present_total, SUM(total_count) as total_total')
            ->groupBy('year', 'week')
            ->orderByDesc('year')
            ->orderByDesc('week')
            ->limit(8)
            ->get();

        return view('admin.attendances.index', [
            'items' => $items,
            'weeklySummary' => $weeklySummary,
        ]);
    }

    public function create(): View
    {
        return view('admin.attendances.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['nullable', 'integer'],
            'date' => ['required', 'date'],
            'class_label' => ['nullable', 'string', 'max:255'],
            'present_count' => ['nullable', 'integer', 'min:0'],
            'total_count' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'recorded_by' => ['nullable', 'integer'],
        ]);

        Attendance::create($data);

        return redirect()->route('admin.attendances.index')->with('status', 'Data kehadiran berhasil disimpan');
    }

    public function edit(Attendance $attendance): View
    {
        return view('admin.attendances.edit', ['attendance' => $attendance]);
    }

    public function update(Request $request, Attendance $attendance)
    {
        $data = $request->validate([
            'student_id' => ['nullable', 'integer'],
            'date' => ['required', 'date'],
            'class_label' => ['nullable', 'string', 'max:255'],
            'present_count' => ['nullable', 'integer', 'min:0'],
            'total_count' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'recorded_by' => ['nullable', 'integer'],
        ]);

        $attendance->update($data);

        return redirect()->route('admin.attendances.index')->with('status', 'Data kehadiran berhasil diperbarui');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->route('admin.attendances.index')->with('status', 'Data kehadiran berhasil dihapus');
    }
}

