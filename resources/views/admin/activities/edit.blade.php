<x-app-layout>
    <div class="p-6">
        <h1 class="text-3xl font-bold mb-6">Edit Kegiatan</h1>

        <form method="POST" action="{{ route('admin.activities.update', $activity) }}" class="bg-white rounded-lg shadow p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Judul -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Judul Kegiatan <span class="text-red-600">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title', $activity->title) }}" required
                           class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Misal: Ibadah Raya, Kelas Kelompok, dll">
                    @error('title')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi / Rincian</label>
                    <textarea name="description" id="description" rows="4"
                              class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Jelaskan detail kegiatan (opsional)">{{ old('description', $activity->description) }}</textarea>
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700">Tanggal <span class="text-red-600">*</span></label>
                    <input type="date" name="date" id="date" value="{{ old('date', $activity->date->format('Y-m-d')) }}" required
                           class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('date')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Waktu (Row) -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                        <input type="time" name="start_time" id="start_time" 
                               value="{{ old('start_time', $activity->start_time ? substr($activity->start_time, 0, 5) : '') }}"
                               class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('start_time')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700">Jam Selesai</label>
                        <input type="time" name="end_time" id="end_time"
                               value="{{ old('end_time', $activity->end_time ? substr($activity->end_time, 0, 5) : '') }}"
                               class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('end_time')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Lokasi -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700">Lokasi</label>
                    <input type="text" name="location" id="location" value="{{ old('location', $activity->location) }}"
                           class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Misal: Ruang Utama, Ruang Kelas 1, Taman, dll">
                    @error('location')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select name="category" id="category"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Ibadah" {{ old('category', $activity->category) === 'Ibadah' ? 'selected' : '' }}>Ibadah</option>
                        <option value="Kelas" {{ old('category', $activity->category) === 'Kelas' ? 'selected' : '' }}>Kelas Kelompok</option>
                        <option value="Renungan" {{ old('category', $activity->category) === 'Renungan' ? 'selected' : '' }}>Renungan Bersama</option>
                        <option value="Games" {{ old('category', $activity->category) === 'Games' ? 'selected' : '' }}>Games / Permainan</option>
                        <option value="Doa" {{ old('category', $activity->category) === 'Doa' ? 'selected' : '' }}>Doa & Saksian</option>
                        <option value="Lainnya" {{ old('category', $activity->category) === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('category')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tombol -->
                <div class="flex gap-3">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.activities.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
