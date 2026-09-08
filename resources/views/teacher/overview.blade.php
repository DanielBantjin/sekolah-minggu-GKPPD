<x-app-layout>
    <div class="p-6">
        <div class="relative overflow-hidden rounded-2xl border border-emerald-200 bg-[linear-gradient(135deg,_#14532d_0%,_#16a34a_45%,_#84cc16_100%)] px-6 py-8 shadow-sm">
            <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-20 -left-10 h-72 w-72 rounded-full bg-white/10"></div>

            <div class="relative">
                <h1 class="text-2xl font-bold text-white sm:text-3xl">Dashboard Guru</h1>
                <p class="mt-2 text-emerald-50">Pantau renungan dan laporan pembacaan murid dengan suasana yang hangat dan penuh semangat.</p>

                <div class="mt-6 flex flex-wrap gap-3">
                    <span class="inline-flex items-center rounded-full border border-white/20 bg-white/15 px-3 py-1 text-sm font-medium text-white">📘 {{ auth()->user()->name }}</span>
                    <span class="inline-flex items-center rounded-full border border-white/20 bg-white/15 px-3 py-1 text-sm font-medium text-white">Role: {{ auth()->user()->role?->name ?? '-' }}</span>
                </div>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('teacher.reading_report.today') }}" class="group block rounded-xl border bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">📋</div>
                    <div>
                        <div class="font-semibold">Laporan Renungan Hari Ini</div>
                        <div class="text-sm text-gray-600">Daftar waktu baca & status selesai.</div>
                    </div>
                </div>
            </a>

            <a href="{{ route('teacher.reflections.index') }}" class="group block rounded-xl border bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">📖</div>
                    <div>
                        <div class="font-semibold">Kelola Renungan</div>
                        <div class="text-sm text-gray-600">Tambah & lihat renungan Anda.</div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>


