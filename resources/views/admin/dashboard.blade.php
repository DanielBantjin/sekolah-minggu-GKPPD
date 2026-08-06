<x-app-layout>
    <div class="space-y-6 p-4 sm:p-6">
        <div class="theme-hero overflow-hidden rounded-3xl border border-[var(--border)] p-6 shadow-xl">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-[var(--white)]/90">Ringkasan Admin</p>
                    <h1 class="mt-2 text-2xl font-bold text-[var(--white)] sm:text-3xl">Dashboard Kehadiran &amp; Keuangan</h1>
                    <p class="mt-2 max-w-2xl text-sm text-[var(--white)]/90 sm:text-base">Lihat gambaran singkat performa sekolah minggu dalam satu halaman dan unduh laporan dengan cepat.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.reports.export-excel', array_filter(['start_date' => $startDate ?? null, 'end_date' => $endDate ?? null], fn ($value) => $value !== null && $value !== '')) }}" class="rounded-xl border border-[var(--white)]/20 bg-[var(--white)]/15 px-4 py-2 text-sm font-semibold text-[var(--white)] shadow-sm backdrop-blur hover:bg-[var(--white)]/25">⬇ Export Excel</a>
                    <a href="{{ route('admin.reports.pdf', array_filter(['start_date' => $startDate ?? null, 'end_date' => $endDate ?? null], fn ($value) => $value !== null && $value !== '')) }}" target="_blank" class="rounded-xl bg-[var(--white)] px-4 py-2 text-sm font-semibold text-[var(--primary)] hover:bg-slate-100">🖨️ Cetak PDF</a>
                </div>
            </div>
        </div>

        <div class="grid gap-4 xl:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-slate-500">Catatan Kehadiran</div>
                <div class="mt-2 text-3xl font-bold text-slate-900">{{ $attendanceSummary['records'] }}</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-slate-500">Persentase Hadir</div>
                <div class="mt-2 text-3xl font-bold text-emerald-600">{{ $attendanceSummary['percentage'] }}%</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-slate-500">Total Pemasukan</div>
                <div class="mt-2 text-2xl font-bold text-green-600">Rp {{ number_format($financeSummary['income'], 0, ',', '.') }}</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-slate-500">Saldo</div>
                <div class="mt-2 text-2xl font-bold {{ $financeSummary['balance'] >= 0 ? 'text-blue-600' : 'text-red-600' }}">Rp {{ number_format($financeSummary['balance'], 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Grafik Kehadiran</h2>
                        <p class="text-sm text-slate-500">Perkembangan kehadiran dalam beberapa catatan terakhir</p>
                    </div>
                    <div class="rounded-full bg-blue-50 px-3 py-1 text-sm font-medium text-blue-700">{{ $attendanceSummary['present'] }}/{{ $attendanceSummary['total'] }} hadir</div>
                </div>

                <form method="GET" action="{{ route('admin.dashboard') }}" class="mt-4 flex flex-wrap items-end gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-3">
                    <input type="hidden" name="period" value="{{ $period }}">
                    <div>
                        <label for="start_date" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Dari</label>
                        <input type="date" id="start_date" name="start_date" value="{{ $startDate ?? '' }}" class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label for="end_date" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Hingga</label>
                        <input type="date" id="end_date" name="end_date" value="{{ $endDate ?? '' }}" class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none">
                    </div>
                    <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Terapkan</button>
                    <a href="{{ route('admin.dashboard', ['period' => $period]) }}" class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">Reset</a>
                </form>

                <div class="mt-4 flex flex-wrap gap-2">
                    <a href="{{ route('admin.dashboard', array_filter(['period' => 'week', 'start_date' => $startDate ?? null, 'end_date' => $endDate ?? null], fn ($value) => $value !== null && $value !== '')) }}" class="rounded-full px-3 py-1.5 text-sm font-medium {{ $period === 'week' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600' }}">Minggu</a>
                    <a href="{{ route('admin.dashboard', array_filter(['period' => 'month', 'start_date' => $startDate ?? null, 'end_date' => $endDate ?? null], fn ($value) => $value !== null && $value !== '')) }}" class="rounded-full px-3 py-1.5 text-sm font-medium {{ $period === 'month' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600' }}">Bulanan</a>
                </div>

                <div class="mt-6 flex h-48 items-end gap-3">
                    @forelse($attendanceTrend as $item)
                        @php $share = $item['total'] > 0 ? round(($item['present'] / $item['total']) * 100, 0) : 0; @endphp
                        <div class="flex flex-1 flex-col items-center">
                            <div class="flex h-36 w-full items-end rounded-2xl bg-slate-100 p-2">
                                <div class="w-full rounded-xl bg-gradient-to-t from-blue-600 to-cyan-400" style="height: {{ max(18, $share) }}%"></div>
                            </div>
                            <div class="mt-2 text-center text-xs font-semibold text-slate-600">{{ $item['label'] }}</div>
                            <div class="text-[11px] text-slate-400">{{ $item['present'] }}/{{ $item['total'] }}</div>
                        </div>
                    @empty
                        <div class="text-sm text-slate-500">Belum ada data kehadiran untuk periode ini.</div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Ringkasan Keuangan</h2>
                <p class="mt-1 text-sm text-slate-500">Pemasukan dan pengeluaran dari data terakhir</p>

                <div class="mt-6 space-y-4">
                    @foreach($monthlyFinance as $item)
                        <div>
                            <div class="mb-1 flex items-center justify-between text-sm">
                                <span class="font-medium text-slate-700">{{ $item['label'] }}</span>
                                <span class="text-slate-500">{{ 'Rp ' . number_format($item['income'], 0, ',', '.') }}</span>
                            </div>
                            <div class="h-2 rounded-full bg-slate-100">
                                <div class="h-2 rounded-full bg-gradient-to-r from-emerald-500 to-green-500" style="width: {{ $item['income'] > 0 ? min(100, round(($item['income'] / max(1, $item['income'] + $item['expense'])) * 100)) : 0 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-900">Kegiatan Terbaru</h2>
                    <a class="text-sm font-medium text-indigo-600" href="{{ route('admin.activities.index') }}">Lihat semua</a>
                </div>
                <div class="mt-4 space-y-3">
                    @forelse($recentActivities as $activity)
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-3">
                            <div class="flex items-center justify-between gap-2">
                                <div class="font-medium text-slate-800">{{ $activity->title }}</div>
                                <div class="text-xs text-slate-500">{{ $activity->date->format('d M Y') }}</div>
                            </div>
                            <div class="mt-1 text-sm text-slate-600">{{ $activity->location ?? 'Lokasi belum ditentukan' }}</div>
                        </div>
                    @empty
                        <div class="text-sm text-slate-500">Belum ada data kegiatan.</div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-900">Transaksi Terbaru</h2>
                    <a class="text-sm font-medium text-indigo-600" href="{{ route('admin.finances.index') }}">Lihat semua</a>
                </div>
                <div class="mt-4 space-y-3">
                    @forelse($recentTransactions as $transaction)
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-3">
                            <div class="flex items-center justify-between gap-2">
                                <div class="font-medium text-slate-800">{{ $transaction->description }}</div>
                                <div class="text-sm font-semibold {{ $transaction->type === 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">{{ $transaction->type === 'pemasukan' ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}</div>
                            </div>
                            <div class="mt-1 text-sm text-slate-600">{{ $transaction->category ?? 'Tanpa kategori' }}</div>
                        </div>
                    @empty
                        <div class="text-sm text-slate-500">Belum ada data keuangan.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


