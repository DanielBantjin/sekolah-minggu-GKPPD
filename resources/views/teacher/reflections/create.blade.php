@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Tambah Renungan Baru</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('teacher.reflections.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-6">
                <label for="title" class="block text-gray-700 font-semibold mb-2">Judul Renungan</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror">
                @error('title')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="bible_verse" class="block text-gray-700 font-semibold mb-2">Ayat Alkitab (Opsional)</label>
                <input type="text" id="bible_verse" name="bible_verse" value="{{ old('bible_verse') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-6">
                <label for="date" class="block text-gray-700 font-semibold mb-2">Tanggal</label>
                <input type="date" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('date') border-red-500 @enderror">
                @error('date')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="content" class="block text-gray-700 font-semibold mb-2">Konten Renungan</label>
                <textarea id="content" name="content" rows="8" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="image" class="block text-gray-700 font-semibold mb-2">Gambar (Opsional)</label>
                <input type="file" id="image" name="image" accept="image/*" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('image') border-red-500 @enderror">
                @error('image')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    Simpan Renungan
                </button>
                <a href="{{ route('teacher.reflections.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
