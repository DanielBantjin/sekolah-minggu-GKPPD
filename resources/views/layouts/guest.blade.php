<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased">
        <div class="min-h-screen flex flex-col items-center justify-center bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.18),_transparent_38%),linear-gradient(135deg,_#f8fbff_0%,_#eef7ff_55%,_#f8fafc_100%)] px-4 py-8 sm:px-6 lg:px-8">
            <div class="w-full overflow-hidden rounded-3xl border border-slate-200 bg-white/90 p-2 shadow-2xl shadow-slate-200/80 sm:max-w-md">
                <div class="rounded-[22px] bg-white p-2">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
