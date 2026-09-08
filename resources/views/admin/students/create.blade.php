<x-app-layout>
    <div class="p-6">
        <h1 class="text-2xl font-semibold">Tambah Student</h1>

        <form method="POST" action="{{ route('admin.students.store') }}" class="mt-4 space-y-3">
            @csrf

            <div>
                <label class="block text-sm font-medium">Nama</label>
                <input class="border rounded w-full p-2" name="name" value="{{ old('name') }}" required>
            </div>

            <button class="px-3 py-2 bg-blue-600 text-white rounded" type="submit">Simpan</button>
            <a class="ml-2 text-sm text-gray-600" href="{{ route('admin.students.index') }}">Kembali</a>
        </form>
    </div>
</x-app-layout>

