<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Attendance;
use App\Models\Finance;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $attendanceRecords = Attendance::query()->orderBy('date')->get();
        $attendanceSummary = [
            'records' => $attendanceRecords->count(),
            'present' => (int) $attendanceRecords->sum('present_count'),
            'total' => (int) $attendanceRecords->sum('total_count'),
            'percentage' => $attendanceRecords->sum('total_count') > 0
                ? round(($attendanceRecords->sum('present_count') / $attendanceRecords->sum('total_count')) * 100, 1)
                : 0,
        ];

        $financeRecords = Finance::query()->orderBy('date')->get();
        $income = (float) $financeRecords->where('type', 'pemasukan')->sum('amount');
        $expense = (float) $financeRecords->where('type', 'pengeluaran')->sum('amount');
        $financeSummary = [
            'records' => $financeRecords->count(),
            'income' => $income,
            'expense' => $expense,
            'balance' => $income - $expense,
        ];

        $attendanceTrend = $attendanceRecords
            ->groupBy(fn ($record) => $record->date->format('d M'))
            ->map(fn ($group, $label) => [
                'label' => $label,
                'present' => (int) $group->sum('present_count'),
                'total' => (int) $group->sum('total_count'),
            ])
            ->take(6)
            ->values();

        $monthlyFinance = $financeRecords
            ->groupBy(fn ($record) => $record->date->format('M Y'))
            ->map(fn ($group, $label) => [
                'label' => $label,
                'income' => (float) $group->where('type', 'pemasukan')->sum('amount'),
                'expense' => (float) $group->where('type', 'pengeluaran')->sum('amount'),
            ])
            ->take(6)
            ->values();

        $recentActivities = Activity::query()->latest('date')->take(5)->get();
        $recentTransactions = Finance::query()->latest('date')->take(5)->get();

        return view('admin.dashboard', compact(
            'user',
            'attendanceSummary',
            'financeSummary',
            'attendanceTrend',
            'monthlyFinance',
            'recentActivities',
            'recentTransactions'
        ));
    }

    public function exportExcel()
    {
        $attendanceRecords = Attendance::query()->orderBy('date')->get();
        $attendanceSummary = [
            'records' => $attendanceRecords->count(),
            'present' => (int) $attendanceRecords->sum('present_count'),
            'total' => (int) $attendanceRecords->sum('total_count'),
            'percentage' => $attendanceRecords->sum('total_count') > 0
                ? round(($attendanceRecords->sum('present_count') / $attendanceRecords->sum('total_count')) * 100, 1)
                : 0,
        ];

        $financeRecords = Finance::query()->orderBy('date')->get();
        $income = (float) $financeRecords->where('type', 'pemasukan')->sum('amount');
        $expense = (float) $financeRecords->where('type', 'pengeluaran')->sum('amount');
        $balance = $income - $expense;

        $rows = [[
            'Laporan Kehadiran dan Keuangan',
            now()->format('d M Y H:i'),
        ]];

        $rows[] = [];
        $rows[] = ['Ringkasan Kehadiran', ''];
        $rows[] = ['Total Catatan', $attendanceSummary['records']];
        $rows[] = ['Jumlah Hadir', $attendanceSummary['present']];
        $rows[] = ['Jumlah Total', $attendanceSummary['total']];
        $rows[] = ['Persentase Hadir', $attendanceSummary['percentage'] . '%'];
        $rows[] = [];
        $rows[] = ['Ringkasan Keuangan', ''];
        $rows[] = ['Total Pemasukan', 'Rp ' . number_format($income, 0, ',', '.')];
        $rows[] = ['Total Pengeluaran', 'Rp ' . number_format($expense, 0, ',', '.')];
        $rows[] = ['Saldo', 'Rp ' . number_format($balance, 0, ',', '.')];

        $fileName = 'laporan-kehadiran-keuangan.xlsx';
        $tempPath = storage_path('app/' . $fileName);
        Excel::store(collect($rows)->map(fn ($row) => $row), $fileName, 'local');

        return response()->download($tempPath, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function pdf()
    {
        $attendanceRecords = Attendance::query()->orderBy('date')->get();
        $attendanceSummary = [
            'records' => $attendanceRecords->count(),
            'present' => (int) $attendanceRecords->sum('present_count'),
            'total' => (int) $attendanceRecords->sum('total_count'),
            'percentage' => $attendanceRecords->sum('total_count') > 0
                ? round(($attendanceRecords->sum('present_count') / $attendanceRecords->sum('total_count')) * 100, 1)
                : 0,
        ];

        $financeRecords = Finance::query()->orderBy('date')->get();
        $income = (float) $financeRecords->where('type', 'pemasukan')->sum('amount');
        $expense = (float) $financeRecords->where('type', 'pengeluaran')->sum('amount');
        $balance = $income - $expense;

        $recentTransactions = Finance::query()->latest('date')->take(10)->get();

        $pdf = Pdf::loadView('admin.reports.pdf', compact(
            'attendanceSummary',
            'income',
            'expense',
            'balance',
            'recentTransactions'
        ));

        return $pdf->download('laporan-kehadiran-keuangan.pdf');
    }
}

