<x-app-layout>
    <div class="p-6">
        <h1 class="text-2xl font-semibold">Kelola Reflections</h1>
        <div class="mt-4">
            <a class="inline-block px-3 py-2 bg-blue-600 text-white rounded" href="{{ route('admin.reflections.create') }}">Tambah</a>
        </div>

        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full text-sm border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 text-left border">ID</th>
                        <th class="p-2 text-left border">Judul</th>
                        <th class="p-2 text-left border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($reflections as $reflection)
                    <tr>
                        <td class="p-2 border">{{ $reflection->id }}</td>
                        <td class="p-2 border">{{ $reflection->title ?? $reflection->name ?? '-' }}</td>
                        <td class="p-2 border">
                            <a class="text-blue-700" href="{{ route('admin.reflections.edit', $reflection) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.reflections.destroy', $reflection) }}" class="inline ml-2">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600" type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="p-2 border text-center text-gray-500">Belum ada data</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $reflections->links() }}</div>
    </div>
</x-app-layout>

