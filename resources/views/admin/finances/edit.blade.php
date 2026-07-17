<x-app-layout>
    <div class="p-6">
        <h1 class="text-3xl font-bold mb-2">Edit Transaksi Keuangan</h1>
        <p class="mb-6 text-sm text-gray-600">Perbarui rincian pemasukan dan pengeluaran sekolah minggu.</p>

        <form method="POST" action="{{ route('admin.finances.update', $finance) }}" class="space-y-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis Transaksi</label>
                    <select name="type" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2" required>
                        <option value="pemasukan" {{ old('type', $finance->type) === 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                        <option value="pengeluaran" {{ old('type', $finance->type) === 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                    <input type="date" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2" name="date" value="{{ old('date', $finance->date->format('Y-m-d')) }}" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                <input class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2" name="description" value="{{ old('description', $finance->description) }}" required>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                    <input type="number" min="0" step="1000" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2" name="amount" value="{{ old('amount', $finance->amount) }}" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Kategori</label>
                    <input class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2" name="category" value="{{ old('category', $finance->category) }}" placeholder="Misal: Donasi, Perlengkapan, Transportasi">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Catatan</label>
                <textarea name="notes" rows="3" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2" placeholder="Catatan tambahan">{{ old('notes', $finance->notes) }}</textarea>
            </div>

            <input type="hidden" name="recorded_by" value="{{ auth()->id() }}">

            <div class="flex gap-3">
                <button class="rounded-xl bg-blue-600 px-4 py-2 font-medium text-white transition hover:bg-blue-700" type="submit">Update</button>
                <a class="rounded-xl bg-gray-200 px-4 py-2 font-medium text-gray-700 transition hover:bg-gray-300" href="{{ route('admin.finances.index') }}">Kembali</a>
            </div>
        </form>
    </div>
</x-app-layout>

