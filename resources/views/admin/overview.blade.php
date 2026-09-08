<x-app-layout>
    <div class="space-y-6">
        <div class="relative overflow-hidden rounded-3xl border border-indigo-200 bg-[linear-gradient(135deg,_#172554_0%,_#4338ca_45%,_#2563eb_100%)] px-6 py-8 shadow-xl">
            <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-20 -left-10 h-72 w-72 rounded-full bg-white/10"></div>

            <div class="relative">
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-indigo-100">Panel Admin</p>
                <h1 class="mt-2 text-2xl font-bold text-white sm:text-3xl">Dashboard Administrator</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-indigo-50 sm:text-base">Kelola data sekolah minggu, mulai dari user, murid, renungan, kegiatan, kehadiran, hingga keuangan dalam satu ruang kerja yang lebih rapi.</p>

                <div class="mt-6 flex flex-wrap gap-3">
                    <span class="inline-flex items-center rounded-full border border-white/20 bg-white/15 px-3 py-1 text-sm font-medium text-white shadow-sm">👑 {{ auth()->user()->name }}</span>
                    <span class="inline-flex items-center rounded-full border border-white/20 bg-white/15 px-3 py-1 text-sm font-medium text-white shadow-sm">Role: {{ auth()->user()->role?->name ?? '-' }}</span>
                </div>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-slate-500">User terdaftar</div>
                <div class="mt-2 text-3xl font-bold text-slate-900">{{ \App\Models\User::count() }}</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-slate-500">Murid terdata</div>
                <div class="mt-2 text-3xl font-bold text-slate-900">{{ \App\Models\Student::count() }}</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-slate-500">Kegiatan minggu</div>
                <div class="mt-2 text-3xl font-bold text-slate-900">{{ \App\Models\Activity::count() }}</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-slate-500">Transaksi keuangan</div>
                <div class="mt-2 text-3xl font-bold text-slate-900">{{ \App\Models\Finance::count() }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            <a href="{{ route('admin.roles.index') }}" class="theme-card group block rounded-2xl border p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-xl text-indigo-700">🛡️</div>
                    <div>
                        <div class="font-semibold text-slate-900">Kelola Role</div>
                        <div class="text-sm text-slate-600">Atur role admin, guru, dan murid.</div>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.users.index') }}" class="group block rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-xl text-indigo-700">👥</div>
                    <div>
                        <div class="font-semibold text-slate-900">Kelola User</div>
                        <div class="text-sm text-slate-600">Tambah dan ubah akun beserta role.</div>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.students.index') }}" class="group block rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-xl text-indigo-700">🎒</div>
                    <div>
                        <div class="font-semibold text-slate-900">Kelola Murid</div>
                        <div class="text-sm text-slate-600">Data murid dan informasi utama.</div>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.reflections.index') }}" class="group block rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-xl text-indigo-700">📖</div>
                    <div>
                        <div class="font-semibold text-slate-900">Kelola Renungan</div>
                        <div class="text-sm text-slate-600">Buat dan aktifkan renungan hari ini.</div>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.reading-tracks.index') }}" class="group block rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-xl text-indigo-700">⏱️</div>
                    <div>
                        <div class="font-semibold text-slate-900">Reading Track</div>
                        <div class="text-sm text-slate-600">Pantau jejak baca murid secara detail.</div>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.activities.index') }}" class="group block rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-xl text-indigo-700">📅</div>
                    <div>
                        <div class="font-semibold text-slate-900">Kelola Kegiatan</div>
                        <div class="text-sm text-slate-600">Jadwal kegiatan mingguan yang menarik.</div>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.attendances.index') }}" class="group block rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-xl text-indigo-700">🗓️</div>
                    <div>
                        <div class="font-semibold text-slate-900">Kelola Kehadiran</div>
                        <div class="text-sm text-slate-600">Catatan hadir, izin, dan sakit.</div>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.finances.index') }}" class="group block rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-xl text-indigo-700">💳</div>
                    <div>
                        <div class="font-semibold text-slate-900">Kelola Keuangan</div>
                        <div class="text-sm text-slate-600">Pemasukan dan pengeluaran terorganisir.</div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>


