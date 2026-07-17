<x-app-layout>
    <div class="p-6">
        <h1 class="text-3xl font-bold mb-2">Edit Kehadiran Mingguan</h1>
        <p class="mb-6 text-sm text-gray-600">Perbarui data absensi per grup kelas untuk minggu yang sama.</p>

        <form method="POST" action="{{ route('admin.attendances.update', $attendance) }}" class="space-y-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Label Kelas</label>
                    <select name="class_label" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2" required>
                        <option value="">-- Pilih Label --</option>
                        <option value="Kelas Kecil" {{ old('class_label', $attendance->class_label) === 'Kelas Kecil' ? 'selected' : '' }}>Kelas Kecil</option>
                        <option value="Kelas Sedang" {{ old('class_label', $attendance->class_label) === 'Kelas Sedang' ? 'selected' : '' }}>Kelas Sedang</option>
                        <option value="Remaja" {{ old('class_label', $attendance->class_label) === 'Remaja' ? 'selected' : '' }}>Remaja</option>
                        <option value="Dewasa" {{ old('class_label', $attendance->class_label) === 'Dewasa' ? 'selected' : '' }}>Dewasa</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                    <input type="date" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2" name="date" value="{{ old('date', $attendance->date->format('Y-m-d')) }}" required>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jumlah Hadir</label>
                    <input type="number" min="0" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2" name="present_count" value="{{ old('present_count', $attendance->present_count) }}" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Total Murid</label>
                    <input type="number" min="0" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2" name="total_count" value="{{ old('total_count', $attendance->total_count) }}" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2">
                    <option value="hadir" {{ old('status', $attendance->status) === 'hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="izin" {{ old('status', $attendance->status) === 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="sakit" {{ old('status', $attendance->status) === 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="tidak_hadir" {{ old('status', $attendance->status) === 'tidak_hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Catatan</label>
                <textarea name="notes" rows="3" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2" placeholder="Tambahkan keterangan jika ada">{{ old('notes', $attendance->notes) }}</textarea>
            </div>

            <input type="hidden" name="recorded_by" value="{{ auth()->id() }}">

            <div class="flex gap-3">
                <button class="rounded-xl bg-blue-600 px-4 py-2 font-medium text-white transition hover:bg-blue-700" type="submit">Update</button>
                <a class="rounded-xl bg-gray-200 px-4 py-2 font-medium text-gray-700 transition hover:bg-gray-300" href="{{ route('admin.attendances.index') }}">Kembali</a>
            </div>
        </form>
    </div>
</x-app-layout>

