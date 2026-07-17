<x-app-layout>
    <div class="p-6">
        <div class="theme-hero relative overflow-hidden rounded-2xl border border-[var(--border)] px-6 py-8 shadow-sm">
            <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-20 -left-10 h-72 w-72 rounded-full bg-white/10"></div>

            <div class="relative">
                <h1 class="text-2xl font-bold text-[var(--white)] sm:text-3xl">Dashboard Murid</h1>
                <p class="mt-2 text-[var(--white)]/90">Baca renungan hari ini dan catat durasi.</p>

                <div class="mt-6 flex flex-wrap gap-3">
                    <span class="inline-flex items-center rounded-full border border-[var(--white)]/20 bg-[var(--white)]/15 px-3 py-1 text-sm font-medium text-[var(--white)]">{{ auth()->user()->name }}</span>
                    <span class="inline-flex items-center rounded-full border border-[var(--white)]/20 bg-[var(--white)]/15 px-3 py-1 text-sm font-medium text-[var(--white)]">Role: {{ auth()->user()->role?->name ?? '-' }}</span>
                </div>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('student.reading.today') }}" class="group block rounded-xl border bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center">📖</div>
                    <div>
                        <div class="font-semibold">Renungan Hari Ini</div>
                        <div class="text-sm text-gray-600">Mulai baca, timer, dan simpan durasi.</div>
                    </div>
                </div>
            </a>

            <a href="{{ route('student.reflections.index') }}" class="group block rounded-xl border bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-sky-50 text-sky-700 flex items-center justify-center">🗂️</div>
                    <div>
                        <div class="font-semibold">Renungan Sebelumnya</div>
                        <div class="text-sm text-gray-600">Lihat renungan yang sudah lewat dan tidak bisa melihat yang belum datang.</div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>


