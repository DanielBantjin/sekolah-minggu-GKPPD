<x-app-layout>
    <div class="p-6">
        <h1 class="text-2xl font-semibold">Tambah Reading Track</h1>

        <form method="POST" action="{{ route('admin.reading-tracks.store') }}" class="mt-4 space-y-3">
            @csrf

            <div>
                <label class="block text-sm font-medium">Murid</label>
                <select class="border rounded w-full p-2" name="student_id" required>
                    <option value="">Pilih murid</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->user?->name ?? 'Murid #' . $student->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium">Renungan</label>
                <select class="border rounded w-full p-2" name="reflection_id">
                    <option value="">Pilih renungan (opsional)</option>
                    @foreach($reflections as $reflection)
                        <option value="{{ $reflection->id }}" {{ old('reflection_id') == $reflection->id ? 'selected' : '' }}>
                            {{ $reflection->title }} ({{ $reflection->date?->format('d M Y') }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium">Tanggal Baca</label>
                <input class="border rounded w-full p-2" type="datetime-local" name="read_at" value="{{ old('read_at') }}" required>
            </div>

            <div>
                <label class="block text-sm font-medium">Durasi (detik)</label>
                <input class="border rounded w-full p-2" type="number" min="0" name="duration_seconds" value="{{ old('duration_seconds', 0) }}">
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="completed" value="1" {{ old('completed') ? 'checked' : '' }}>
                <label class="text-sm">Selesai</label>
            </div>

            <button class="px-3 py-2 bg-blue-600 text-white rounded" type="submit">Simpan</button>
            <a class="ml-2 text-sm text-gray-600" href="{{ route('admin.reading-tracks.index') }}">Kembali</a>
        </form>
    </div>
</x-app-layout>

