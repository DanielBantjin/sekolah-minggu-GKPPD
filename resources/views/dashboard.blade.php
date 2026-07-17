<x-app-layout>
    <div class="space-y-6">
        <section class="theme-hero overflow-hidden rounded-3xl p-8 shadow-xl">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-[var(--white)]/90">Selamat datang</p>
                    <h1 class="mt-2 text-3xl font-bold text-[var(--white)] sm:text-4xl">Halo, {{ auth()->user()->name }} 👋</h1>
                    <p class="mt-3 max-w-2xl text-sm text-[var(--white)]/90 sm:text-base">
                        Sistem informasi Sekolah Minggu GKPPD siap membantu mengelola renungan, kegiatan, kehadiran, dan keuangan dengan lebih teratur.
                    </p>
                </div>

                <div class="rounded-2xl border border-[var(--white)]/20 bg-[var(--white)]/15 px-4 py-3 shadow-sm backdrop-blur">
                    <div class="text-sm text-[var(--white)]/80">Peran saat ini</div>
                    <div class="text-lg font-semibold text-[var(--white)]">{{ auth()->user()->role?->name ?? 'User' }}</div>
                </div>
            </div>
        </section>

        <div class="grid gap-4 lg:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-sm font-medium text-slate-500">Status sistem</div>
                <div class="mt-2 text-2xl font-bold text-slate-900">Aktif hari ini</div>
                <p class="mt-2 text-sm text-slate-600">Data renungan, kegiatan, dan absensi dapat dipantau dari satu dashboard.</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-sm font-medium text-slate-500">Fokus utama</div>
                <div class="mt-2 text-2xl font-bold text-slate-900">Mudah dipakai</div>
                <p class="mt-2 text-sm text-slate-600">Tampilan diperbarui agar lebih rapi, bersih, dan konsisten di semua modul.</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-sm font-medium text-slate-500">Akses cepat</div>
                <div class="mt-2 text-2xl font-bold text-slate-900">Satu klik</div>
                <p class="mt-2 text-sm text-slate-600">Buka halaman yang paling sering dipakai langsung dari dashboard.</p>
            </div>
        </div>

        @if(auth()->user()->role?->name === 'admin')
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <a href="{{ route('admin.roles.index') }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <div class="text-sm font-semibold text-slate-900">Kelola Role</div>
                    <p class="mt-2 text-sm text-slate-600">Atur hak akses admin, guru, dan murid.</p>
                </a>
                <a href="{{ route('admin.reflections.index') }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <div class="text-sm font-semibold text-slate-900">Renungan</div>
                    <p class="mt-2 text-sm text-slate-600">Buat dan kelola materi renungan mingguan.</p>
                </a>
                <a href="{{ route('admin.activities.index') }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <div class="text-sm font-semibold text-slate-900">Kegiatan</div>
                    <p class="mt-2 text-sm text-slate-600">Atur agenda kegiatan dan acara sekolah minggu.</p>
                </a>
                <a href="{{ route('admin.finances.index') }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <div class="text-sm font-semibold text-slate-900">Keuangan</div>
                    <p class="mt-2 text-sm text-slate-600">Pantau pemasukan dan pengeluaran secara rapi.</p>
                </a>
            </div>
        @elseif(auth()->user()->role?->name === 'guru')
            <div class="grid gap-4 md:grid-cols-2">
                <a href="{{ url('/teacher/reading_report/today') }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <div class="text-sm font-semibold text-slate-900">Laporan Renungan</div>
                    <p class="mt-2 text-sm text-slate-600">Lihat siapa yang sudah membaca dan durasi pembacaan.</p>
                </a>
                <a href="{{ url('/admin/activities') }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <div class="text-sm font-semibold text-slate-900">Kegiatan Minggu</div>
                    <p class="mt-2 text-sm text-slate-600">Pantau agenda kegiatan yang akan datang.</p>
                </a>
            </div>
        @else
            <div class="grid gap-4 md:grid-cols-2">
                <a href="{{ url('/student/reading/today') }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <div class="text-sm font-semibold text-slate-900">Renungan Hari Ini</div>
                    <p class="mt-2 text-sm text-slate-600">Buka renungan dan lanjutkan bacaan Anda.</p>
                </a>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="text-sm font-semibold text-slate-900">Informasi</div>
                    <p class="mt-2 text-sm text-slate-600">Kehadiran dan aktivitas Anda akan tercatat melalui sistem.</p>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
