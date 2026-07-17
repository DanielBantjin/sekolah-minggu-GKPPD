<x-app-layout>
    <div class="p-4 sm:p-6">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold sm:text-3xl">Kelola Kegiatan</h1>
                <p class="mt-1 text-sm text-gray-600">Jadwal kegiatan Sekolah Minggu</p>
            </div>
            <a class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700" href="{{ route('admin.activities.create') }}">
                + Tambah Kegiatan
            </a>
        </div>

        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <div class="text-sm text-gray-600">Total Kegiatan</div>
                    <div class="text-2xl font-bold text-blue-600 sm:text-3xl">{{ \App\Models\Activity::count() }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Minggu Ini</div>
                    <div class="text-2xl font-bold text-green-600 sm:text-3xl">
                        {{ \App\Models\Activity::whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])->count() }}
                    </div>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Bulan Ini</div>
                    <div class="text-2xl font-bold text-purple-600 sm:text-3xl">
                        {{ \App\Models\Activity::whereMonth('date', now()->month)->count() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="block space-y-3 p-3 md:hidden">
                @forelse ($activities as $activity)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="font-semibold text-slate-900">{{ $activity->title }}</div>
                                <div class="mt-1 text-sm text-slate-600">{{ $activity->date->format('d M Y') }}</div>
                            </div>
                            @if($activity->category)
                                <span class="rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-700">{{ $activity->category }}</span>
                            @endif
                        </div>
                        <div class="mt-3 space-y-1 text-sm text-slate-600">
                            <div>Waktu: {{ $activity->start_time && $activity->end_time ? substr($activity->start_time, 0, 5) . ' - ' . substr($activity->end_time, 0, 5) : '-' }}</div>
                            <div>Lokasi: {{ $activity->location ?? '-' }}</div>
                        </div>
                        <div class="mt-3 flex gap-2">
                            <a href="{{ route('admin.activities.edit', $activity) }}" class="rounded-lg bg-blue-100 px-3 py-2 text-xs font-medium text-blue-700">Edit</a>
                            <form method="POST" action="{{ route('admin.activities.destroy', $activity) }}" onsubmit="return confirm('Hapus kegiatan ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="rounded-lg bg-red-100 px-3 py-2 text-xs font-medium text-red-700" type="submit">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-sm text-gray-500">Belum ada kegiatan</div>
                @endforelse
            </div>

            <div class="hidden overflow-x-auto md:block">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50">
                            <th class="p-3 text-left font-semibold text-slate-700">No.</th>
                            <th class="p-3 text-left font-semibold text-slate-700">Judul Kegiatan</th>
                            <th class="p-3 text-left font-semibold text-slate-700">Tanggal</th>
                            <th class="p-3 text-left font-semibold text-slate-700">Waktu</th>
                            <th class="p-3 text-left font-semibold text-slate-700">Lokasi</th>
                            <th class="p-3 text-left font-semibold text-slate-700">Kategori</th>
                            <th class="p-3 text-center font-semibold text-slate-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($activities as $index => $activity)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="p-3 text-slate-600">{{ $activities->firstItem() + $index }}</td>
                            <td class="p-3 font-medium text-slate-900">{{ $activity->title }}</td>
                            <td class="p-3 text-slate-600">{{ $activity->date->format('d M Y') }}</td>
                            <td class="p-3 text-sm text-slate-600">
                                @if($activity->start_time && $activity->end_time)
                                    {{ substr($activity->start_time, 0, 5) }} - {{ substr($activity->end_time, 0, 5) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="p-3 text-sm text-slate-600">{{ $activity->location ?? '-' }}</td>
                            <td class="p-3">
                                @if($activity->category)
                                    <span class="inline-block rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-700">{{ $activity->category }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <a href="{{ route('admin.activities.edit', $activity) }}" class="mr-2 text-xs font-medium text-blue-600 hover:underline">Edit</a>
                                <form method="POST" action="{{ route('admin.activities.destroy', $activity) }}" class="inline" onsubmit="return confirm('Hapus kegiatan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs font-medium text-red-600 hover:underline" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center text-gray-500">
                                <div class="text-lg">Belum ada kegiatan</div>
                                <p class="mt-1 text-sm">Mulai tambahkan kegiatan Sekolah Minggu</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($activities->hasPages())
            <div class="mt-6">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
