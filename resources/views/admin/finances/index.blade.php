<x-app-layout>
    <div class="space-y-6 p-4 sm:p-6">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-bold sm:text-3xl">Manajemen Keuangan</h1>
                <p class="mt-1 text-sm text-gray-600">Pantau pemasukan, pengeluaran, dan saldo sekolah minggu dengan ringkasan yang jelas.</p>
            </div>
            <a class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2 font-medium text-white transition hover:bg-blue-700" href="{{ route('admin.finances.create') }}">
                + Tambah Transaksi
            </a>
        </div>

        @php
            $incomes = $items->getCollection()->where('type', 'pemasukan')->sum('amount');
            $expenses = $items->getCollection()->where('type', 'pengeluaran')->sum('amount');
            $balance = $incomes - $expenses;
        @endphp

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="text-sm text-slate-500">Total Pemasukan</div>
                <div class="mt-2 text-2xl font-bold text-green-600 sm:text-3xl">Rp {{ number_format($incomes, 0, ',', '.') }}</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="text-sm text-slate-500">Total Pengeluaran</div>
                <div class="mt-2 text-2xl font-bold text-red-600 sm:text-3xl">Rp {{ number_format($expenses, 0, ',', '.') }}</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="text-sm text-slate-500">Saldo</div>
                <div class="mt-2 text-2xl font-bold {{ $balance >= 0 ? 'text-blue-600' : 'text-red-600' }} sm:text-3xl">Rp {{ number_format($balance, 0, ',', '.') }}</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="text-sm text-slate-500">Total Transaksi</div>
                <div class="mt-2 text-2xl font-bold text-purple-600 sm:text-3xl">{{ $items->total() }}</div>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
            <h2 class="mb-3 text-lg font-semibold">Ringkasan Bulanan</h2>
            <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                @forelse($monthlySummary as $summary)
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <div class="text-sm font-semibold text-slate-700">{{ \Carbon\Carbon::create($summary->year, $summary->month, 1)->translatedFormat('F Y') }}</div>
                        <div class="mt-2 text-lg font-bold text-green-600">Masuk: Rp {{ number_format($summary->income_total ?? 0, 0, ',', '.') }}</div>
                        <div class="text-lg font-bold text-red-600">Keluar: Rp {{ number_format($summary->expense_total ?? 0, 0, ',', '.') }}</div>
                    </div>
                @empty
                    <div class="text-sm text-slate-500">Belum ada ringkasan bulanan.</div>
                @endforelse
            </div>
        </div>

        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="block space-y-3 p-3 md:hidden">
                @forelse ($items as $finance)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="font-semibold text-slate-900">{{ $finance->description }}</div>
                                <div class="mt-1 text-sm text-slate-600">{{ $finance->date->format('d M Y') }}</div>
                            </div>
                            <div class="text-sm font-semibold {{ $finance->type === 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $finance->type === 'pemasukan' ? '+' : '-' }} Rp {{ number_format($finance->amount, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="mt-3 flex flex-wrap items-center gap-2 text-sm text-slate-600">
                            @if($finance->category)
                                <span class="rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-700">{{ $finance->category }}</span>
                            @endif
                            @if($finance->type === 'pemasukan')
                                <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700">Masuk</span>
                            @else
                                <span class="rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-700">Keluar</span>
                            @endif
                        </div>
                        <div class="mt-3 flex gap-2">
                            <a href="{{ route('admin.finances.edit', $finance) }}" class="rounded-lg bg-blue-100 px-3 py-2 text-xs font-medium text-blue-700">Edit</a>
                            <form method="POST" action="{{ route('admin.finances.destroy', $finance) }}" onsubmit="return confirm('Hapus transaksi ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="rounded-lg bg-red-100 px-3 py-2 text-xs font-medium text-red-700" type="submit">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-sm text-slate-500">Belum ada transaksi keuangan</div>
                @endforelse
            </div>

            <div class="hidden overflow-x-auto md:block">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50">
                            <th class="p-3 text-left font-semibold text-slate-700">No.</th>
                            <th class="p-3 text-left font-semibold text-slate-700">Tanggal</th>
                            <th class="p-3 text-left font-semibold text-slate-700">Deskripsi</th>
                            <th class="p-3 text-left font-semibold text-slate-700">Kategori</th>
                            <th class="p-3 text-right font-semibold text-slate-700">Jumlah</th>
                            <th class="p-3 text-center font-semibold text-slate-700">Tipe</th>
                            <th class="p-3 text-center font-semibold text-slate-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($items as $index => $finance)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="p-3 text-slate-600">{{ $items->firstItem() + $index }}</td>
                            <td class="p-3 text-slate-600">{{ $finance->date->format('d M Y') }}</td>
                            <td class="p-3 font-medium text-slate-900">{{ $finance->description }}</td>
                            <td class="p-3 text-sm text-slate-600">
                                @if($finance->category)
                                    <span class="inline-block rounded bg-blue-100 px-2 py-1 text-xs text-blue-700">{{ $finance->category }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="p-3 text-right font-semibold {{ $finance->type === 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $finance->type === 'pemasukan' ? '+' : '-' }} Rp {{ number_format($finance->amount, 0, ',', '.') }}
                            </td>
                            <td class="p-3 text-center">
                                @if($finance->type === 'pemasukan')
                                    <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">⬆ Masuk</span>
                                @else
                                    <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">⬇ Keluar</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <a href="{{ route('admin.finances.edit', $finance) }}" class="mr-2 text-xs font-medium text-blue-600 hover:underline">Edit</a>
                                <form method="POST" action="{{ route('admin.finances.destroy', $finance) }}" class="inline" onsubmit="return confirm('Hapus transaksi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs font-medium text-red-600 hover:underline" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center text-slate-500">
                                <div class="text-lg font-medium">Belum ada transaksi keuangan</div>
                                <p class="mt-1 text-sm">Mulai input pemasukan dan pengeluaran</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($items->hasPages())
            <div class="mt-6">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

