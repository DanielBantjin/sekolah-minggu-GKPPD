<nav x-data="{ open: false }" class="border-b border-[var(--border)] bg-[var(--navbar)]/90 backdrop-blur shadow-sm">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,var(--primary),var(--secondary))] text-lg font-semibold text-[var(--white)] shadow-sm">✝</span>
            <span class="text-base font-semibold text-[var(--heading)]">Sekolah Minggu</span>
        </a>

        <div class="hidden items-center gap-2 sm:flex">
            <a href="{{ route('dashboard') }}" class="rounded-full px-3 py-2 text-sm font-medium text-[var(--text)] transition hover:bg-[var(--card-hover)] hover:text-[var(--heading)]">Dashboard</a>
            @if(auth()->user()->role?->name === 'admin')
                <a href="{{ url('/admin') }}" class="rounded-full px-3 py-2 text-sm font-medium text-[var(--text)] transition hover:bg-[var(--card-hover)] hover:text-[var(--heading)]">Admin</a>
            @endif
            <a href="{{ route('profile.edit') }}" class="rounded-full px-3 py-2 text-sm font-medium text-[var(--text)] transition hover:bg-[var(--card-hover)] hover:text-[var(--heading)]">Profil</a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="rounded-full bg-[var(--primary)] px-3 py-2 text-sm font-medium text-[var(--white)] transition hover:bg-[var(--primary-hover)]">Keluar</button>
            </form>
        </div>

        <div class="flex items-center sm:hidden">
            <button @click="open = ! open" class="rounded-lg p-2 text-[var(--text)] transition hover:bg-[var(--card-hover)]">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="border-t border-[var(--border)] bg-[var(--navbar)] px-4 py-3 sm:hidden">
        <div class="flex flex-col gap-2">
            <a href="{{ route('dashboard') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-[var(--text)] hover:bg-[var(--card-hover)]">Dashboard</a>
            @if(auth()->user()->role?->name === 'admin')
                <a href="{{ url('/admin') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-[var(--text)] hover:bg-[var(--card-hover)]">Admin</a>
            @endif
            <a href="{{ route('profile.edit') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-[var(--text)] hover:bg-[var(--card-hover)]">Profil</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full rounded-lg bg-[var(--primary)] px-3 py-2 text-left text-sm font-medium text-[var(--white)]">Keluar</button>
            </form>
        </div>
    </div>
</nav>
