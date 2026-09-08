<x-app-layout>
    <div class="p-6">
        <h1 class="text-2xl font-semibold">Kelola Students</h1>
        <div class="mt-4">
            <a class="inline-block px-3 py-2 bg-blue-600 text-white rounded" href="{{ route('admin.students.create') }}">Tambah</a>
        </div>

        @if (session('status'))
            <div class="mt-4 p-3 bg-green-100 text-green-800 rounded text-sm">{{ session('status') }}</div>
        @endif

        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full text-sm border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 text-left border">ID</th>
                        <th class="p-2 text-left border">Nama</th>
                        <th class="p-2 text-left border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($students as $student)
                    <tr>
                        <td class="p-2 border">{{ $student->id }}</td>
                        <td class="p-2 border">{{ $student->name ?? $student->full_name ?? '-' }}</td>
                        <td class="p-2 border">
                            <a class="text-blue-700" href="{{ route('admin.students.edit', $student) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.students.destroy', $student) }}" class="inline ml-2">
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

        <div class="mt-4">{{ $students->links() }}</div>
    </div>
</x-app-layout>

