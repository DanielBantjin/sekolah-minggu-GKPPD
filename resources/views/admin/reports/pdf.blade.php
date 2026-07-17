<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Kehadiran dan Keuangan</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111827; }
        h1 { font-size: 20px; margin-bottom: 8px; }
        .card { border: 1px solid #e5e7eb; padding: 12px; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #e5e7eb; padding: 8px; text-align: left; font-size: 12px; }
        th { background: #f3f4f6; }
    </style>
</head>
<body>
    <h1>Laporan Kehadiran dan Keuangan</h1>
    <p>Dicetak pada {{ now()->format('d M Y H:i') }}</p>

    <div class="card">
        <strong>Ringkasan Kehadiran</strong>
        <p>Total Catatan: {{ $attendanceSummary['records'] }}</p>
        <p>Jumlah Hadir: {{ $attendanceSummary['present'] }}</p>
        <p>Persentase Hadir: {{ $attendanceSummary['percentage'] }}%</p>
    </div>

    <div class="card">
        <strong>Ringkasan Keuangan</strong>
        <p>Total Pemasukan: Rp {{ number_format($income, 0, ',', '.') }}</p>
        <p>Total Pengeluaran: Rp {{ number_format($expense, 0, ',', '.') }}</p>
        <p>Saldo: Rp {{ number_format($balance, 0, ',', '.') }}</p>
    </div>

    <div class="card">
        <strong>Transaksi Terbaru</strong>
        <table>
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentTransactions as $transaction)
                    <tr>
                        <td>{{ $transaction->description }}</td>
                        <td>{{ $transaction->date->format('d M Y') }}</td>
                        <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
