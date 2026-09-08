<x-app-layout>
    <div class="space-y-6">
        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-indigo-600">Laporan Renungan</p>
                    <h1 class="mt-2 text-3xl font-bold text-slate-900">Pantau Pembacaan Per Renungan</h1>
                    <p class="mt-2 text-sm text-slate-600">Tanggal: {{ \Carbon\Carbon::parse($reflection->date)->format('d F Y') }}</p>
                </div>
                <div class="rounded-2xl border border-indigo-100 bg-indigo-50 p-4 text-sm text-slate-700">
                    <div class="font-semibold text-indigo-700">Renungan</div>
                    <div class="mt-1 font-medium">{{ $reflection->title }}</div>
                    @if($reflection->bible_verse)
                        <div class="mt-1 text-xs text-slate-500">{{ $reflection->bible_verse }}</div>
                    @endif
                </div>
            </div>

            <form method="GET" action="{{ route('teacher.reading_report.today') }}" class="mt-6 flex flex-col gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4 md:flex-row md:items-end">
                <div class="flex-1">
                    <label for="reflection_id" class="mb-1 block text-sm font-medium text-slate-700">Pilih renungan</label>
                    <select id="reflection_id" name="reflection_id" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        @foreach($reflections as $item)
                            <option value="{{ $item->id }}" @selected($reflection->id == $item->id)>
                                {{ $item->title }} — {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700">
                    Lihat Laporan
                </button>
            </form>
        </section>

        <div class="grid gap-4 md:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="text-sm text-slate-500">Total Pembacaan</div>
                <div class="mt-2 text-3xl font-bold text-indigo-600">{{ $tracks->total() }}</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="text-sm text-slate-500">Selesai</div>
                <div class="mt-2 text-3xl font-bold text-green-600">{{ $tracks->getCollection()->where('completed', true)->count() }}</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="text-sm text-slate-500">Rata-rata Durasi</div>
                <div class="mt-2 text-3xl font-bold text-orange-600">
                    @php
                        $avgSeconds = $tracks->getCollection()->avg('duration_seconds');
                        $avgMinutes = floor($avgSeconds / 60);
                        $avgSecs = $avgSeconds % 60;
                    @endphp
                    {{ (int)$avgMinutes }}m{{ (int)$avgSecs }}s
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="text-sm text-slate-500">Total Durasi</div>
                <div class="mt-2 text-3xl font-bold text-purple-600">
                    @php
                        $totalSeconds = $tracks->getCollection()->sum('duration_seconds');
                        $hours = floor($totalSeconds / 3600);
                        $minutes = floor(($totalSeconds % 3600) / 60);
                    @endphp
                    {{ $hours > 0 ? $hours . 'h ' : '' }}{{ $minutes }}m
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50">
                            <th class="p-3 text-left font-semibold text-slate-700">No.</th>
                            <th class="p-3 text-left font-semibold text-slate-700">Nama Murid</th>
                            <th class="p-3 text-left font-semibold text-slate-700">Waktu Baca</th>
                            <th class="p-3 text-left font-semibold text-slate-700">Durasi</th>
                            <th class="p-3 text-center font-semibold text-slate-700">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($tracks as $index => $track)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="p-3 text-slate-600">{{ $index + 1 }}</td>
                            <td class="p-3 font-medium text-slate-900">{{ $track->student?->user?->name ?? '-' }}</td>
                            <td class="p-3 text-slate-600">{{ \Carbon\Carbon::parse($track->read_at)->format('H:i:s') }}</td>
                            <td class="p-3 font-semibold text-slate-900">
                                @php
                                    $mins = floor($track->duration_seconds / 60);
                                    $secs = $track->duration_seconds % 60;
                                @endphp
                                {{ $mins }}m {{ $secs }}s
                            </td>
                            <td class="p-3 text-center">
                                @if($track->completed)
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-700">✓ Selesai</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-700">Sedang</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-slate-500">
                                <div class="text-lg font-medium">Belum ada data pembacaan hari ini</div>
                                <p class="mt-1 text-sm">Murid akan muncul di sini setelah membuka renungan</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($tracks->hasPages())
            <div class="mt-6">
                {{ $tracks->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

