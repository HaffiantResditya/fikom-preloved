<x-guest-layout>
    <!-- Navigasi Cepat Kembali -->
    <div class="w-full max-w-[430px] mx-auto mb-2 text-left">
        <a href="{{ route('home') }}" class="inline-flex items-center text-xs text-gray-500 hover:text-gray-800 transition font-medium">
            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Beranda
        </a>
    </div>

    <!-- Card Auth Container -->
    <div class="fikom-card max-w-[430px] my-2">
        <div class="p-7 sm:p-8">
            <!-- LOGO -->
            <div class="flex justify-center mb-1">
                <a href="{{ route('home') }}" class="inline-block">
                    <img src="{{ asset('images/logo_fikompreloved.svg') }}" 
                         alt="Logo FIKOM PRELOVED" 
                         class="fikom-logo"
                         style="height: 88px; max-height: 92px; width: auto; object-fit: contain;">
                </a>
            </div>

            <!-- HEADER -->
            <div class="text-center mb-5">
                <h1 class="text-base sm:text-lg font-bold tracking-tight text-gray-900 uppercase">
                    MASUK KE AKUN KAMPUS
                </h1>
                <p class="text-[11px] sm:text-xs text-gray-500 mt-0.5">
                    Gunakan email Student FIKOM untuk mulai jual &amp; beli
                </p>
            </div>

            <!-- Session Status Alert -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- FORM LOGIN -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email Kampus -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-700 mb-1">Email Kampus</label>
                    <div class="relative flex items-center">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               autocomplete="username" 
                               placeholder="farispratama@mhs.udb.ac.id"
                               oninput="checkLoginEmailHint(this.value)"
                               class="fikom-input" />
                    </div>
                    <!-- Real-time gentle hint -->
                    <div id="email-hint" class="hidden mt-1 text-[11px] text-amber-600 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Mahasiswa diwajibkan menggunakan email resmi kampus (@mhs.udb.ac.id)</span>
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
                </div>

                <!-- Kata Sandi -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-gray-700 mb-1">Kata Sandi</label>
                    <div class="relative flex items-center">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="current-password" 
                               placeholder="Masukkan Sandi"
                               class="fikom-input fikom-input-password" />
                        <button type="button" 
                                onclick="togglePasswordVisibility('password', 'eye-icon-login')" 
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg id="eye-icon-login" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
                </div>

                <!-- Ingatkan Saya & Lupa Sandi -->
                <div class="flex items-center justify-between text-xs pt-0.5">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer text-gray-600 select-none">
                        <input id="remember_me" 
                               type="checkbox" 
                               name="remember" 
                               class="rounded border-gray-300 text-[#1e4b8a] shadow-sm focus:ring-[#1e4b8a]">
                        <span class="ms-2 text-xs text-gray-600">Ingatkan saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="font-bold text-[#1e4b8a] hover:underline" href="{{ route('password.request') }}">
                            Lupa Kata Sandi?
                        </a>
                    @endif
                </div>

                <!-- Tombol Submit -->
                <div class="pt-1">
                    <button type="submit" class="fikom-btn-primary">
                        Masuk ke Akun Kampus
                    </button>
                </div>
            </form>

            <!-- SEPARATOR -->
            <div class="fikom-divider">
                <hr>
                <span>atau</span>
            </div>

            <!-- LINK REGISTRASI -->
            <div class="text-center text-xs space-y-1">
                <p class="text-gray-500">Belum daftar akun kampus?</p>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="block font-bold text-red-600 hover:underline text-sm">
                        Daftar akun kampus disini!
                    </a>
                @endif
            </div>
        </div>

        <!-- FOOTER CARD -->
        <div class="py-3 text-center border-t border-gray-100 bg-gray-50 text-[10px] text-gray-400">
            &copy; 2026 FIKOM PRELOVED &mdash; Universitas Duta Bangsa Surakarta
        </div>
    </div>

    <!-- Script lokal untuk hint email -->
    <script>
        function checkLoginEmailHint(val) {
            const hint = document.getElementById('email-hint');
            if (!hint) return;
            if (val.includes('@') && !val.toLowerCase().endsWith('@mhs.udb.ac.id')) {
                hint.classList.remove('hidden');
            } else {
                hint.classList.add('hidden');
            }
        }
    </script>
</x-guest-layout>