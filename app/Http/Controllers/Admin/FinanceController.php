<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Finance;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index(): View
    {
        $items = Finance::query()->orderByDesc('date')->paginate(20);

        $monthlySummary = Finance::query()
            ->selectRaw('YEAR(date) as year, MONTH(date) as month, SUM(CASE WHEN type = "pemasukan" THEN amount ELSE 0 END) as income_total, SUM(CASE WHEN type = "pengeluaran" THEN amount ELSE 0 END) as expense_total')
            ->groupBy('year', 'month')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->limit(6)
            ->get();

        return view('admin.finances.index', [
            'items' => $items,
            'monthlySummary' => $monthlySummary,
        ]);
    }

    public function create(): View
    {
        return view('admin.finances.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['required', 'integer'],
            'type' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'amount' => ['required', 'numeric'],
            'date' => ['required', 'date'],
            'category' => ['nullable', 'string', 'max:255'],
            'recorded_by' => ['nullable', 'integer'],
            'notes' => ['nullable', 'string'],
        ]);

        Finance::create($data);

        return redirect()->route('admin.finances.index')->with('status', 'Finance created');
    }

    public function edit(Finance $finance): View
    {
        return view('admin.finances.edit', ['item' => $finance]);
    }

    public function update(Request $request, Finance $finance)
    {
        $data = $request->validate([
            'student_id' => ['required', 'integer'],
            'type' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'amount' => ['required', 'numeric'],
            'date' => ['required', 'date'],
            'category' => ['nullable', 'string', 'max:255'],
            'recorded_by' => ['nullable', 'integer'],
            'notes' => ['nullable', 'string'],
        ]);

        $finance->update($data);

        return redirect()->route('admin.finances.index')->with('status', 'Finance updated');
    }

    public function destroy(Finance $finance)
    {
        $finance->delete();
        return redirect()->route('admin.finances.index')->with('status', 'Finance deleted');
    }
}

