<x-guest-layout>
    <div class="w-full max-w-5xl overflow-hidden rounded-[32px] border border-slate-200 bg-white shadow-2xl shadow-slate-200/70">
        <div class="grid min-h-[560px] lg:grid-cols-[1.05fr_0.95fr]">
            <div class="relative flex items-center justify-center overflow-hidden bg-[linear-gradient(135deg,_#0f172a_0%,_#1d4ed8_45%,_#7c3aed_100%)] p-8 text-white sm:p-10">
                <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(circle at 20% 20%, rgba(255,255,255,0.18) 1px, transparent 1px); background-size: 22px 22px;"></div>
                <div class="relative z-10 text-center">
                    <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-3xl border border-white/30 bg-white/15 backdrop-blur">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 4v5c0 5-3.5 8.5-7 9-3.5-.5-7-4-7-9V7l7-4z" />
                        </svg>
                    </div>
                    <h1 class="text-3xl font-semibold">Selamat Datang</h1>
                    <p class="mt-3 text-sm leading-6 text-slate-100 sm:text-base">Masuk ke sistem tracking renungan sekolah minggu dengan tampilan yang lebih modern dan nyaman.</p>
                    <div class="mt-6 inline-flex items-center rounded-full border border-white/20 bg-slate-900/95 px-3 py-1 text-sm font-semibold text-white shadow-lg shadow-black/20 backdrop-blur">
                        GKPPD • Sekolah Minggu
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-center bg-slate-50/70 p-6 sm:p-8 lg:p-10">
                <div class="w-full max-w-md">
                    <div class="mb-6 text-center lg:text-left">
                        <p class="text-sm font-semibold uppercase tracking-[0.25em] text-indigo-600">Login</p>
                        <h2 class="mt-2 text-2xl font-semibold text-slate-900">Masuk ke akun Anda</h2>
                        <p class="mt-2 text-sm text-slate-600">Silakan gunakan email dan password Anda untuk melanjutkan.</p>
                    </div>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <div>
                            <x-input-label for="email" :value="__('Email')" class="text-slate-700" />
                            <x-text-input id="email" class="mt-1 block w-full rounded-2xl border-slate-300 bg-white px-3 py-2.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password" :value="__('Password')" class="text-slate-700" />
                            <x-text-input id="password" class="mt-1 block w-full rounded-2xl border-slate-300 bg-white px-3 py-2.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" type="password" name="password" required autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="inline-flex items-center">
                                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                                <span class="ms-2 text-sm text-slate-600">{{ __('Remember me') }}</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a class="text-sm font-medium text-indigo-600 hover:text-indigo-700" href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-[linear-gradient(135deg,_#2563eb_0%,_#7c3aed_100%)] px-4 py-2.5 font-semibold text-white transition hover:opacity-90">
                                {{ __('Log in') }}
                            </button>
                        </div>
                    </form>

                    @if (Route::has('register'))
                        <div class="mt-5 text-center text-sm text-slate-600">
                            Belum punya akun?
                            <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-700">Register</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

