@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Daftar Renungan</h1>
        <a href="{{ route('teacher.reflections.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
            + Tambah Renungan
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($reflections->count() > 0)
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($reflections as $reflection)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    @if($reflection->image)
                        <div class="h-40 bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $reflection->image) }}')"></div>
                    @else
                        <div class="h-40 bg-gradient-to-r from-blue-400 to-blue-600"></div>
                    @endif
                    <div class="p-4">
                        <p class="text-sm text-gray-500 mb-1">{{ $reflection->date->format('d M Y') }}</p>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $reflection->title }}</h3>
                        <p class="text-gray-600 text-sm line-clamp-3">{{ $reflection->content }}</p>
                        @if($reflection->bible_verse)
                            <p class="text-sm text-blue-600 mt-2 font-semibold">{{ $reflection->bible_verse }}</p>
                        @endif

                        <div class="mt-4 flex flex-wrap gap-2">
                            <a href="{{ route('teacher.reflections.edit', $reflection) }}" class="rounded-lg bg-sky-600 px-3 py-2 text-sm font-medium text-white hover:bg-sky-700">Edit</a>
                            <form action="{{ route('teacher.reflections.destroy', $reflection) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus renungan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-lg bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-700">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $reflections->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <p class="text-gray-500">Belum ada renungan. <a href="{{ route('teacher.reflections.create') }}" class="text-blue-600 hover:underline">Buat yang baru</a></p>
        </div>
    @endif
</div>
@endsection
