<x-guest-layout>
    <!-- Card Auth Container -->
    <div class="w-full max-w-md bg-white rounded-3xl shadow-lg border border-gray-200 overflow-hidden my-4 mx-auto">
        <div class="p-8">
            <!-- LOGO -->
            <div class="flex justify-center mb-2">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/logo_fikompreloved.svg') }}" 
                         alt="Logo FIKOM PRELOVED" 
                         class="h-28 object-contain">
                </a>
            </div>

            <!-- HEADER -->
            <div class="text-center mb-6">
                <h1 class="text-lg font-bold tracking-tight text-gray-900 uppercase">MASUK KE AKUN KAMPUS</h1>
                <p class="text-xs text-gray-500 mt-0.5">Gunakan email Student FIKOM untuk mulai jual &amp; beli</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- FORM LOGIN -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email Kampus -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-700 mb-1">Email Kampus</label>
                    <div class="relative">
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
                               class="w-full pl-10 pr-3 py-2 text-xs border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-800 focus:border-blue-800" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
                </div>

                <!-- Kata Sandi -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-gray-700 mb-1">Kata Sandi</label>
                    <div class="relative">
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
                               placeholder="Masukkan kata sandi"
                               class="w-full pl-10 pr-10 py-2 text-xs border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-800 focus:border-blue-800" />
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
                <div class="flex items-center justify-between text-xs pt-1">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer text-gray-600">
                        <input id="remember_me" 
                               type="checkbox" 
                               name="remember" 
                               class="rounded border-gray-300 text-blue-800 shadow-sm focus:ring-blue-800">
                        <span class="ms-2 text-xs text-gray-600">{{ __('Ingatkan saya') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="font-bold text-blue-900 hover:underline" href="{{ route('password.request') }}">
                            {{ __('Lupa Kata Sandi?') }}
                        </a>
                    @endif
                </div>

                <!-- Tombol Submit -->
                <div class="pt-2">
                    <button type="submit" class="w-full bg-[#1e40af] hover:bg-blue-900 text-white font-semibold py-2.5 rounded-full transition duration-200 text-sm shadow">
                        {{ __('Masuk via SSO') }}
                    </button>
                </div>
            </form>

            <!-- SEPARATOR -->
            <div class="relative my-6 text-center">
                <hr class="border-gray-200">
                <span class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white px-3 text-xs text-gray-400">atau</span>
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

    <!-- Script toggle intip kata sandi -->
    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />`;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
            }
        }
    </script>
</x-guest-layout>