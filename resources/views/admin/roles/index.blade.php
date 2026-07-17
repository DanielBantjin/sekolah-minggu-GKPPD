<x-app-layout>
    <div class="p-6">
        <h1 class="text-2xl font-semibold">Kelola Role</h1>
        <div class="mt-4">
            <a class="inline-block px-3 py-2 bg-blue-600 text-white rounded" href="{{ route('admin.roles.create') }}">Tambah</a>
        </div>

        @if (session('status'))
            <div class="mt-4 p-3 bg-green-100 text-green-800 rounded text-sm">{{ session('status') }}</div>
        @endif

        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full text-sm border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 text-left border">ID</th>
                        <th class="p-2 text-left border">Name</th>
                        <th class="p-2 text-left border">Description</th>
                        <th class="p-2 text-left border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td class="p-2 border">{{ $role->id }}</td>
                        <td class="p-2 border">{{ $role->name }}</td>
                        <td class="p-2 border">{{ $role->description }}</td>
                        <td class="p-2 border space-x-2">
                            <a class="text-blue-700" href="{{ route('admin.roles.edit', $role) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600" type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $roles->links() }}</div>
    </div>
</x-app-layout>

