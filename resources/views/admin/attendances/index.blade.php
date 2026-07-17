<x-app-layout>
    <div class="space-y-6 p-4 sm:p-6">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-bold sm:text-3xl">Kelola Kehadiran Mingguan</h1>
                <p class="mt-1 text-sm text-gray-600">Input data per label kelas dan lihat ringkasan mingguan secara cepat.</p>
            </div>
            <a class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2 font-medium text-white transition hover:bg-blue-700" href="{{ route('admin.attendances.create') }}">
                + Input Kehadiran
            </a>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="text-sm text-slate-500">Total Catatan</div>
                <div class="mt-2 text-2xl font-bold text-blue-600 sm:text-3xl">{{ $items->total() }}</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="text-sm text-slate-500">Hadir</div>
                <div class="mt-2 text-2xl font-bold text-green-600 sm:text-3xl">{{ $items->getCollection()->sum('present_count') }}</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="text-sm text-slate-500">Total Murid</div>
                <div class="mt-2 text-2xl font-bold text-purple-600 sm:text-3xl">{{ $items->getCollection()->sum('total_count') }}</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="text-sm text-slate-500">Persentase Hadir</div>
                <div class="mt-2 text-2xl font-bold text-emerald-600 sm:text-3xl">
                    @php
                        $present = $items->getCollection()->sum('present_count');
                        $total = $items->getCollection()->sum('total_count');
                        $percentage = $total > 0 ? round(($present / $total) * 100, 1) : 0;
                    @endphp
                    {{ $percentage }}%
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
            <h2 class="mb-3 text-lg font-semibold">Ringkasan Mingguan</h2>
            <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                @forelse($weeklySummary as $summary)
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <div class="text-sm font-semibold text-slate-700">Minggu {{ $summary->week }} / {{ $summary->year }}</div>
                        <div class="mt-2 text-xl font-bold text-blue-600">{{ $summary->present_total ?? 0 }} hadir</div>
                        <div class="text-sm text-slate-500">dari {{ $summary->total_total ?? 0 }} total</div>
                    </div>
                @empty
                    <div class="text-sm text-slate-500">Belum ada ringkasan mingguan.</div>
                @endforelse
            </div>
        </div>

        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="block space-y-3 p-3 md:hidden">
                @forelse ($items as $attendance)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="font-semibold text-slate-900">{{ $attendance->class_label ?? '-' }}</div>
                                <div class="mt-1 text-sm text-slate-600">{{ $attendance->date->format('d M Y') }}</div>
                            </div>
                            <div class="rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700">{{ $attendance->present_count ?? 0 }} / {{ $attendance->total_count ?? 0 }}</div>
                        </div>
                        <div class="mt-3 text-sm text-slate-600">Catatan: {{ $attendance->notes ?? '-' }}</div>
                        <div class="mt-3 flex gap-2">
                            <a href="{{ route('admin.attendances.edit', $attendance) }}" class="rounded-lg bg-blue-100 px-3 py-2 text-xs font-medium text-blue-700">Edit</a>
                            <form method="POST" action="{{ route('admin.attendances.destroy', $attendance) }}" onsubmit="return confirm('Hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="rounded-lg bg-red-100 px-3 py-2 text-xs font-medium text-red-700" type="submit">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-sm text-slate-500">Belum ada data kehadiran</div>
                @endforelse
            </div>

            <div class="hidden overflow-x-auto md:block">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50">
                            <th class="p-3 text-left font-semibold text-slate-700">No.</th>
                            <th class="p-3 text-left font-semibold text-slate-700">Label Kelas</th>
                            <th class="p-3 text-left font-semibold text-slate-700">Tanggal</th>
                            <th class="p-3 text-left font-semibold text-slate-700">Hadir</th>
                            <th class="p-3 text-left font-semibold text-slate-700">Total</th>
                            <th class="p-3 text-left font-semibold text-slate-700">Catatan</th>
                            <th class="p-3 text-center font-semibold text-slate-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($items as $index => $attendance)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="p-3 text-slate-600">{{ $items->firstItem() + $index }}</td>
                            <td class="p-3 font-medium text-slate-900">{{ $attendance->class_label ?? '-' }}</td>
                            <td class="p-3 text-slate-600">{{ $attendance->date->format('d M Y') }}</td>
                            <td class="p-3 font-semibold text-green-600">{{ $attendance->present_count ?? 0 }}</td>
                            <td class="p-3 text-slate-600">{{ $attendance->total_count ?? 0 }}</td>
                            <td class="p-3 text-sm text-slate-600">{{ $attendance->notes ?? '-' }}</td>
                            <td class="p-3 text-center">
                                <a href="{{ route('admin.attendances.edit', $attendance) }}" class="mr-2 text-xs font-medium text-blue-600 hover:underline">Edit</a>
                                <form method="POST" action="{{ route('admin.attendances.destroy', $attendance) }}" class="inline" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs font-medium text-red-600 hover:underline" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center text-slate-500">
                                <div class="text-lg font-medium">Belum ada data kehadiran</div>
                                <p class="mt-1 text-sm">Mulai input kehadiran per kelas</p>
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

