<x-app-layout>
    <div class="p-6">
        <h1 class="text-2xl font-semibold">Edit Reading Track</h1>

        <form method="POST" action="{{ route('admin.reading-tracks.update', $reading_track) }}" class="mt-4 space-y-3">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium">Nama</label>
                <input class="border rounded w-full p-2" name="name" value="{{ old('name', $reading_track->name) }}" required>
            </div>

            <button class="px-3 py-2 bg-blue-600 text-white rounded" type="submit">Update</button>
            <a class="ml-2 text-sm text-gray-600" href="{{ route('admin.reading-tracks.index') }}">Kembali</a>
        </form>
    </div>
</x-app-layout>

