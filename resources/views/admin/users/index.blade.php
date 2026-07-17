<x-app-layout>
    <div class="p-6">
        <h1 class="text-2xl font-semibold">Kelola User</h1>
        <div class="mt-4">
            <a class="inline-block px-3 py-2 bg-blue-600 text-white rounded" href="{{ route('admin.users.create') }}">Tambah</a>
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
                        <th class="p-2 text-left border">Email</th>
                        <th class="p-2 text-left border">Role</th>
                        <th class="p-2 text-left border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td class="p-2 border">{{ $user->id }}</td>
                        <td class="p-2 border">{{ $user->name }}</td>
                        <td class="p-2 border">{{ $user->email }}</td>
                        <td class="p-2 border">{{ $user->role?->name }}</td>
                        <td class="p-2 border">
                            <a class="text-blue-700" href="{{ route('admin.users.edit', $user) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline ml-2">
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

        <div class="mt-4">{{ $users->links() }}</div>
    </div>
</x-app-layout>

