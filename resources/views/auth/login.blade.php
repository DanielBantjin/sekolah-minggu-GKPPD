<x-guest-layout>
    <div class="w-full max-w-md">
        <div class="mb-6 text-center">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-600 text-white shadow-lg shadow-sky-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v3m0 0v-3m0 0H9m3 0h3m-6.5-3.5h7c.83 0 1.5-.67 1.5-1.5V7.5c0-.83-.67-1.5-1.5-1.5h-7c-.83 0-1.5.67-1.5 1.5v2.5c0 .83.67 1.5 1.5 1.5Zm0 0v-2.5m0 0V6m0 0H5.5A1.5 1.5 0 0 0 4 7.5v9A1.5 1.5 0 0 0 5.5 18h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 18.5 6H15" />
                </svg>
            </div>
            <h1 class="text-2xl font-semibold text-slate-900">Masuk ke akun</h1>
            <p class="mt-1 text-sm text-slate-600">Selamat datang kembali, mari lanjutkan aktivitas hari ini 👋</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <div class="rounded-2xl border border-slate-200 bg-white/95 p-6 shadow-xl shadow-slate-200/70 backdrop-blur">
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-slate-700" />
                    <x-text-input id="email" class="mt-1 block w-full rounded-xl border-slate-300 bg-slate-50 px-3 py-2.5 shadow-sm focus:border-sky-500 focus:ring-sky-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password" :value="__('Password')" class="text-slate-700" />
                    <x-text-input id="password" class="mt-1 block w-full rounded-xl border-slate-300 bg-slate-50 px-3 py-2.5 shadow-sm focus:border-sky-500 focus:ring-sky-500" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-sky-600 shadow-sm focus:ring-sky-500" name="remember">
                        <span class="ms-2 text-sm text-slate-600">{{ __('Remember me') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm font-medium text-sky-600 hover:text-sky-700" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>

                <div class="pt-2">
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-sky-600 px-4 py-2.5 font-semibold text-white transition hover:bg-sky-700">
                        {{ __('Log in') }}
                    </button>
                </div>
            </form>
        </div>

        @if (Route::has('register'))
            <div class="mt-4 text-center text-sm text-slate-600">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-sky-600 hover:text-sky-700">Register</a>
            </div>
        @endif
    </div>
</x-guest-layout>

