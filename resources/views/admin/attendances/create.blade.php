<x-app-layout>
    <div class="p-6">
        <h1 class="text-3xl font-bold mb-2">Input Kehadiran Mingguan</h1>
        <p class="mb-6 text-sm text-gray-600">Masukkan data kehadiran per kelompok kelas, misalnya Kelas Kecil, Sedang, Remaja. Sistem akan menghitung total kehadiran dan persentase.</p>

        <form method="POST" action="{{ route('admin.attendances.store') }}" class="space-y-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Label Kelas</label>
                    <select name="class_label" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2" required>
                        <option value="">-- Pilih Label --</option>
                        <option value="Kelas Kecil">Kelas Kecil</option>
                        <option value="Kelas Sedang">Kelas Sedang</option>
                        <option value="Remaja">Remaja</option>
                        <option value="Dewasa">Dewasa</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                    <input type="date" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2" name="date" value="{{ old('date', now()->toDateString()) }}" required>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jumlah Hadir</label>
                    <input type="number" min="0" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2" name="present_count" value="{{ old('present_count') }}" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Total Murid</label>
                    <input type="number" min="0" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2" name="total_count" value="{{ old('total_count') }}" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2">
                    <option value="hadir">Hadir</option>
                    <option value="izin">Izin</option>
                    <option value="sakit">Sakit</option>
                    <option value="tidak_hadir">Tidak Hadir</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Catatan</label>
                <textarea name="notes" rows="3" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2" placeholder="Tambahkan keterangan jika ada"></textarea>
            </div>

            <input type="hidden" name="recorded_by" value="{{ auth()->id() }}">

            <div class="flex gap-3">
                <button class="rounded-xl bg-blue-600 px-4 py-2 font-medium text-white transition hover:bg-blue-700" type="submit">Simpan</button>
                <a class="rounded-xl bg-gray-200 px-4 py-2 font-medium text-gray-700 transition hover:bg-gray-300" href="{{ route('admin.attendances.index') }}">Kembali</a>
            </div>
        </form>
    </div>
</x-app-layout>

