<x-app-layout>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold">Laporan Tracking Pembacaan</h1>
                <p class="text-gray-600 mt-1">Monitoring durasi pembacaan renungan semua murid</p>
            </div>
            <a class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition" href="{{ route('admin.reading-tracks.create') }}">
                + Tambah Manual
            </a>
        </div>

        <!-- Filter & Stats -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <div class="text-sm text-gray-600">Total Pembacaan</div>
                    <div class="text-3xl font-bold text-blue-600">{{ $items->total() }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Selesai Dibaca</div>
                    <div class="text-3xl font-bold text-green-600">
                        {{ $items->getCollection()->where('completed', true)->count() }}
                    </div>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Total Durasi</div>
                    <div class="text-3xl font-bold text-purple-600">
                        @php
                            $total = $items->getCollection()->sum('duration_seconds');
                            $h = floor($total / 3600);
                            $m = floor(($total % 3600) / 60);
                        @endphp
                        {{ $h > 0 ? $h . 'h ' : '' }}{{ $m }}m
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="p-3 text-left">No.</th>
                            <th class="p-3 text-left">Nama Murid</th>
                            <th class="p-3 text-left">Renungan</th>
                            <th class="p-3 text-left">Tanggal Baca</th>
                            <th class="p-3 text-left">Durasi</th>
                            <th class="p-3 text-center">Status</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($items as $index => $track)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 text-gray-600">{{ $items->firstItem() + $index }}</td>
                            <td class="p-3 font-medium">
                                {{ $track->student?->user?->name ?? '-' }}
                                <div class="text-xs text-gray-500">{{ $track->student?->student_id ?? '-' }}</div>
                            </td>
                            <td class="p-3">
                                {{ $track->reflection?->title ?? '-' }}
                                <div class="text-xs text-gray-500">{{ $track->reflection?->date ?? '-' }}</div>
                            </td>
                            <td class="p-3 text-sm">
                                {{ \Carbon\Carbon::parse($track->read_at)->format('d M Y H:i') }}
                            </td>
                            <td class="p-3 font-semibold">
                                @php
                                    $mins = floor($track->duration_seconds / 60);
                                    $secs = $track->duration_seconds % 60;
                                @endphp
                                <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded">
                                    {{ $mins }}m {{ $secs }}s
                                </span>
                            </td>
                            <td class="p-3 text-center">
                                @if($track->completed)
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                        ✓ Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700">
                                        Sedang
                                    </span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <a href="{{ route('admin.reading-tracks.edit', $track) }}" class="text-blue-600 hover:underline text-xs">Edit</a>
                                <form method="POST" action="{{ route('admin.reading-tracks.destroy', $track) }}" class="inline ml-2" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline text-xs" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center text-gray-500">
                                <div class="text-lg">Belum ada data pembacaan</div>
                                <p class="text-sm mt-1">Data akan muncul ketika murid membaca renungan</p>
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

