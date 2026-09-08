<x-app-layout>
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-[var(--heading)]">Renungan Sebelumnya</h1>
            <p class="mt-2 text-sm text-[var(--text-light)]">Anda hanya bisa melihat renungan yang sudah lewat dan tersedia untuk dibaca.</p>
        </div>

        @if($reflections->count() > 0)
            <div class="space-y-4">
                @foreach($reflections as $reflection)
                    <a href="{{ route('student.reflections.show', $reflection) }}" class="block rounded-2xl border border-[var(--border)] bg-[var(--card)] p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <div class="text-sm font-semibold text-[var(--primary)]">{{ $reflection->date->format('d M Y') }}</div>
                                <h2 class="mt-1 text-lg font-semibold text-[var(--heading)]">{{ $reflection->title }}</h2>
                                <p class="mt-2 text-sm text-[var(--text-light)]">{{ Str::limit(strip_tags($reflection->content), 140) }}</p>
                            </div>
                            <span class="inline-flex items-center rounded-full bg-[var(--primary)]/10 px-3 py-1 text-sm font-medium text-[var(--primary)]">Buka</span>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $reflections->links() }}
            </div>
        @else
            <div class="rounded-2xl border border-[var(--border)] bg-[var(--card)] p-8 text-center text-sm text-[var(--text-light)]">
                Belum ada renungan sebelumnya yang bisa dibuka.
            </div>
        @endif
    </div>
</x-app-layout>
