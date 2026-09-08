<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Attendance;
use App\Models\Finance;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $period = in_array($request->query('period'), ['week', 'month'], true) ? $request->query('period') : 'week';
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $attendanceRecords = Attendance::query()
            ->when($startDate, function ($query, $value) {
                $query->whereDate('date', '>=', $value);
            })
            ->when($endDate, function ($query, $value) {
                $query->whereDate('date', '<=', $value);
            })
            ->orderBy('date')
            ->get();

        $attendanceSummary = [
            'records' => $attendanceRecords->count(),
            'present' => (int) $attendanceRecords->sum('present_count'),
            'total' => (int) $attendanceRecords->sum('total_count'),
            'percentage' => $attendanceRecords->sum('total_count') > 0
                ? round(($attendanceRecords->sum('present_count') / $attendanceRecords->sum('total_count')) * 100, 1)
                : 0,
        ];

        $financeRecords = Finance::query()
            ->when($startDate, function ($query, $value) {
                $query->whereDate('date', '>=', $value);
            })
            ->when($endDate, function ($query, $value) {
                $query->whereDate('date', '<=', $value);
            })
            ->orderBy('date')
            ->get();

        $income = (float) $financeRecords->where('type', 'pemasukan')->sum('amount');
        $expense = (float) $financeRecords->where('type', 'pengeluaran')->sum('amount');
        $financeSummary = [
            'records' => $financeRecords->count(),
            'income' => $income,
            'expense' => $expense,
            'balance' => $income - $expense,
        ];

        $attendanceTrend = $attendanceRecords
            ->groupBy(function ($record) use ($period) {
                $date = Carbon::parse($record->date);

                return $period === 'month'
                    ? $date->format('Y-m')
                    : $date->startOfWeek()->format('Y-m-d');
            })
            ->map(function ($group, $key) use ($period) {
                $date = Carbon::createFromFormat($period === 'month' ? 'Y-m' : 'Y-m-d', $key);

                $label = $period === 'month'
                    ? $date->translatedFormat('M Y')
                    : $date->translatedFormat('d M') . ' - ' . $date->copy()->addDays(6)->translatedFormat('d M');

                return [
                    'label' => $label,
                    'present' => (int) $group->sum('present_count'),
                    'total' => (int) $group->sum('total_count'),
                ];
            })
            ->sortBy(fn ($item) => $item['label'])
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

        $recentActivities = Activity::query()
            ->when($startDate, function ($query, $value) {
                $query->whereDate('date', '>=', $value);
            })
            ->when($endDate, function ($query, $value) {
                $query->whereDate('date', '<=', $value);
            })
            ->latest('date')
            ->take(5)
            ->get();

        $recentTransactions = Finance::query()
            ->when($startDate, function ($query, $value) {
                $query->whereDate('date', '>=', $value);
            })
            ->when($endDate, function ($query, $value) {
                $query->whereDate('date', '<=', $value);
            })
            ->latest('date')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'user',
            'attendanceSummary',
            'financeSummary',
            'attendanceTrend',
            'monthlyFinance',
            'recentActivities',
            'recentTransactions',
            'period',
            'startDate',
            'endDate'
        ));
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $attendanceRecords = Attendance::query()
            ->when($startDate, function ($query, $value) {
                $query->whereDate('date', '>=', $value);
            })
            ->when($endDate, function ($query, $value) {
                $query->whereDate('date', '<=', $value);
            })
            ->orderBy('date')
            ->get();
        $attendanceSummary = [
            'records' => $attendanceRecords->count(),
            'present' => (int) $attendanceRecords->sum('present_count'),
            'total' => (int) $attendanceRecords->sum('total_count'),
            'percentage' => $attendanceRecords->sum('total_count') > 0
                ? round(($attendanceRecords->sum('present_count') / $attendanceRecords->sum('total_count')) * 100, 1)
                : 0,
        ];

        $financeRecords = Finance::query()
            ->when($startDate, function ($query, $value) {
                $query->whereDate('date', '>=', $value);
            })
            ->when($endDate, function ($query, $value) {
                $query->whereDate('date', '<=', $value);
            })
            ->orderBy('date')
            ->get();

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

        $fileName = 'laporan-kehadiran-keuangan.csv';

        $handle = fopen('php://temp', 'r+');
        foreach ($rows as $row) {
            fputcsv($handle, $row, ';');
        }
        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content, 200)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    public function pdf(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $attendanceRecords = Attendance::query()
            ->when($startDate, function ($query, $value) {
                $query->whereDate('date', '>=', $value);
            })
            ->when($endDate, function ($query, $value) {
                $query->whereDate('date', '<=', $value);
            })
            ->orderBy('date')
            ->get();
        $attendanceSummary = [
            'records' => $attendanceRecords->count(),
            'present' => (int) $attendanceRecords->sum('present_count'),
            'total' => (int) $attendanceRecords->sum('total_count'),
            'percentage' => $attendanceRecords->sum('total_count') > 0
                ? round(($attendanceRecords->sum('present_count') / $attendanceRecords->sum('total_count')) * 100, 1)
                : 0,
        ];

        $financeRecords = Finance::query()
            ->when($startDate, function ($query, $value) {
                $query->whereDate('date', '>=', $value);
            })
            ->when($endDate, function ($query, $value) {
                $query->whereDate('date', '<=', $value);
            })
            ->orderBy('date')
            ->get();

        $income = (float) $financeRecords->where('type', 'pemasukan')->sum('amount');
        $expense = (float) $financeRecords->where('type', 'pengeluaran')->sum('amount');
        $balance = $income - $expense;

        $recentTransactions = Finance::query()
            ->when($startDate, function ($query, $value) {
                $query->whereDate('date', '>=', $value);
            })
            ->when($endDate, function ($query, $value) {
                $query->whereDate('date', '<=', $value);
            })
            ->latest('date')
            ->take(10)
            ->get();

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

