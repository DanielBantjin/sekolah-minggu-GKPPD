<x-app-layout>
    <div class="p-6">
        <div class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-6 shadow-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-[var(--primary)]">Renungan sebelumnya</p>
                    <h1 class="mt-2 text-3xl font-bold text-[var(--heading)]">{{ $reflection->title }}</h1>
                    <p class="mt-2 text-sm text-[var(--text-light)]">Tanggal: {{ $reflection->date->format('d F Y') }}</p>
                </div>

                @if($reflection->bible_verse)
                    <div class="rounded-2xl border border-[var(--border)] bg-[var(--table-header)] px-4 py-3 text-sm">
                        <div class="font-semibold text-[var(--heading)]">Ayat</div>
                        <div class="mt-1 text-[var(--text)]">{{ $reflection->bible_verse }}</div>
                    </div>
                @endif
            </div>

            <div class="prose prose-slate mt-6 max-w-none text-base leading-8">
                {!! nl2br(e($reflection->content)) !!}
            </div>
        </div>
    </div>
</x-app-layout>
