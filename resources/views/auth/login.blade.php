<x-guest-layout>
    <!-- Card Auth Container -->
    <div class="w-full max-w-md bg-white rounded-3xl shadow-lg border border-gray-200 overflow-hidden my-4 mx-auto">
        <div class="p-8">
            <!-- LOGO -->
            <!-- LOGO -->
           <div class="flex justify-center mb-2">
              <img src="{{ asset('assets/images/logo_fikompreloved.jpg') }}" 
                   alt="Logo FIKOM PRELOVED" 
                 class="h-28 object-contain">
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
                    <x-input-label for="email" :value="__('Email Kampus')" class="text-xs font-semibold text-gray-700 mb-1" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 text-xs">
                            ✉️
                        </div>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               :value="old('email')" 
                               required 
                               autofocus 
                               autocomplete="username" 
                               placeholder="nama@mhs.udb.ac.id"
                               class="w-full pl-9 pr-3 py-2 text-xs border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-800 focus:border-blue-800" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
                </div>

                <!-- Kata Sandi -->
                <div>
                    <x-input-label for="password" :value="__('Kata Sandi')" class="text-xs font-semibold text-gray-700 mb-1" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 text-xs">
                            🔒
                        </div>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="current-password" 
                               placeholder="Masukkan kata sandi"
                               class="w-full pl-9 pr-9 py-2 text-xs border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-800 focus:border-blue-800" />
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 text-xs">
                            👁️
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

    <!-- Script singkat toggle intip kata sandi -->
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        }
    </script>
</x-guest-layout>