<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen bg-[var(--background)] text-[var(--text)] antialiased">
    @auth
        @include('layouts.navigation')
    @else
        <header class="border-b border-[var(--border)] bg-[var(--navbar)]/90 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <a href="/" class="flex items-center gap-3 font-semibold text-[var(--heading)]">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,var(--primary),var(--secondary))] text-lg text-[var(--white)] shadow-sm">✝</span>
                    <span>Sekolah Minggu GKPPD</span>
                </a>
                <nav class="flex items-center gap-4 text-sm">
                    <a class="rounded-full bg-[var(--primary)]/10 px-4 py-2 font-medium text-[var(--primary)] transition hover:bg-[var(--primary)]/20" href="{{ route('login') }}">Login</a>
                </nav>
            </div>
        </header>
    @endauth

    <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        {{ $slot ?? '' }}
        @yield('content')
    </main>
</body>
</html>


