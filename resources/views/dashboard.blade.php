<x-app-layout>
    <div class="space-y-6">
        <section class="hero-glow overflow-hidden rounded-3xl border border-indigo-200 bg-[linear-gradient(135deg,_#172554_0%,_#4338ca_45%,_#2563eb_100%)] p-8 shadow-xl">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-indigo-100">Selamat datang</p>
                    <h1 class="mt-2 text-3xl font-bold text-white sm:text-4xl">Halo, {{ auth()->user()->name }} 👋</h1>
                    <p class="mt-3 max-w-2xl text-sm text-indigo-50 sm:text-base">
                        Sistem informasi Sekolah Minggu GKPPD siap membantu mengelola renungan, kegiatan, kehadiran, dan keuangan dengan lebih teratur.
                    </p>
                </div>

                <div class="rounded-2xl border border-white/20 bg-white/15 px-4 py-3 shadow-sm backdrop-blur">
                    <div class="text-sm text-indigo-100">Peran saat ini</div>
                    <div class="text-lg font-semibold text-white">{{ auth()->user()->role?->name ?? 'User' }}</div>
                </div>
            </div>
        </section>

        <div class="grid gap-4 lg:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-sm font-medium text-slate-500">📖 Status sistem</div>
                <div class="mt-2 text-2xl font-bold text-slate-900">Aktif hari ini</div>
                <p class="mt-2 text-sm text-slate-600">Data renungan, kegiatan, dan absensi dapat dipantau dari satu dashboard.</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-sm font-medium text-slate-500">✨ Fokus utama</div>
                <div class="mt-2 text-2xl font-bold text-slate-900">Mudah dipakai</div>
                <p class="mt-2 text-sm text-slate-600">Tampilan diperbarui agar lebih rapi, bersih, dan konsisten di semua modul.</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-sm font-medium text-slate-500">⚡ Akses cepat</div>
                <div class="mt-2 text-2xl font-bold text-slate-900">Satu klik</div>
                <p class="mt-2 text-sm text-slate-600">Buka halaman yang paling sering dipakai langsung dari dashboard.</p>
            </div>
        </div>

        @if(auth()->user()->role?->name === 'admin')
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <a href="{{ route('admin.roles.index') }}" class="hover-lift rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition">
                    <div class="text-sm font-semibold text-slate-900">🛡️ Kelola Role</div>
                    <p class="mt-2 text-sm text-slate-600">Atur hak akses admin, guru, dan murid.</p>
                </a>
                <a href="{{ route('admin.reflections.index') }}" class="hover-lift rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition">
                    <div class="text-sm font-semibold text-slate-900">📖 Renungan</div>
                    <p class="mt-2 text-sm text-slate-600">Buat dan kelola materi renungan mingguan.</p>
                </a>
                <a href="{{ route('admin.activities.index') }}" class="hover-lift rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition">
                    <div class="text-sm font-semibold text-slate-900">📅 Kegiatan</div>
                    <p class="mt-2 text-sm text-slate-600">Atur agenda kegiatan dan acara sekolah minggu.</p>
                </a>
                <a href="{{ route('admin.finances.index') }}" class="hover-lift rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition">
                    <div class="text-sm font-semibold text-slate-900">💳 Keuangan</div>
                    <p class="mt-2 text-sm text-slate-600">Pantau pemasukan dan pengeluaran secara rapi.</p>
                </a>
            </div>
        @elseif(auth()->user()->role?->name === 'guru')
            <div class="grid gap-4 md:grid-cols-2">
                <a href="{{ url('/teacher/reading_report/today') }}" class="hover-lift rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition">
                    <div class="text-sm font-semibold text-slate-900">📋 Laporan Renungan</div>
                    <p class="mt-2 text-sm text-slate-600">Lihat siapa yang sudah membaca dan durasi pembacaan.</p>
                </a>
                <a href="{{ url('/admin/activities') }}" class="hover-lift rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition">
                    <div class="text-sm font-semibold text-slate-900">📅 Kegiatan Minggu</div>
                    <p class="mt-2 text-sm text-slate-600">Pantau agenda kegiatan yang akan datang.</p>
                </a>
            </div>
        @else
            <div class="grid gap-4 md:grid-cols-2">
                <a href="{{ url('/student/reading/today') }}" class="hover-lift rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition">
                    <div class="text-sm font-semibold text-slate-900">📖 Renungan Hari Ini</div>
                    <p class="mt-2 text-sm text-slate-600">Buka renungan dan lanjutkan bacaan Anda.</p>
                </a>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="text-sm font-semibold text-slate-900">🌟 Informasi</div>
                    <p class="mt-2 text-sm text-slate-600">Kehadiran dan aktivitas Anda akan tercatat melalui sistem.</p>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
