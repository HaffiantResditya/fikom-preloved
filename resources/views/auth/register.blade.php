<x-guest-layout>
    <!-- Card Auth Container -->
    <div class="w-full max-w-lg bg-white rounded-3xl shadow-lg border border-gray-200 overflow-hidden my-4 mx-auto">
        <div class="p-8">
            <!-- LOGO -->
            <div class="flex justify-center mb-2">
                <a href="/">
                    <img src="{{ asset('images/logo_fikompreloved.svg') }}" 
                         alt="Logo FIKOM PRELOVED" 
                         class="h-28 object-contain">
                </a>
            </div>

            <!-- HEADER -->
            <div class="text-center mb-6">
                <h1 class="text-lg font-bold tracking-tight text-gray-900 uppercase">BUAT AKUN KAMPUS</h1>
                <p class="text-xs text-gray-500 mt-0.5">Satu akun untuk jual &amp; beli Preloved sesama Mahasiswa FIKOM</p>
            </div>

            <!-- FORM REGISTER -->
            <form method="POST" action="{{ route('register') }}" class="space-y-3.5">
                @csrf

                <!-- Baris 1: Nama Lengkap & No. Whatsapp (2 Kolom) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <input id="name" 
                                   type="text" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required 
                                   autofocus 
                                   autocomplete="name" 
                                   placeholder="Faris Pratama Putra"
                                   class="w-full pl-10 pr-3 py-2 text-xs border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-800 focus:border-blue-800" />
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs" />
                    </div>

                    <!-- No. Whatsapp -->
                    <div>
                        <label for="whatsapp_number" class="block text-xs font-semibold text-gray-700 mb-1">No. Whatsapp</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <input id="whatsapp_number" 
                                   type="text" 
                                   name="whatsapp_number" 
                                   value="{{ old('whatsapp_number') }}" 
                                   required 
                                   placeholder="081319534692"
                                   class="w-full pl-10 pr-3 py-2 text-xs border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-800 focus:border-blue-800" />
                        </div>
                        <x-input-error :messages="$errors->get('whatsapp_number')" class="mt-1 text-xs" />
                    </div>
                </div>

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
                               autocomplete="username" 
                               placeholder="farispratama@mhs.udb.ac.id"
                               class="w-full pl-10 pr-3 py-2 text-xs border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-800 focus:border-blue-800" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
                </div>

                <!-- Kata Sandi -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="text-xs font-semibold text-gray-700">Kata sandi</label>
                        <span class="text-[11px] text-gray-400">Minimal 8 karakter</span>
                    </div>
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
                               autocomplete="new-password" 
                               placeholder="Amb4tuk4m"
                               class="w-full pl-10 pr-10 py-2 text-xs border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-800 focus:border-blue-800" />
                        <button type="button" 
                                onclick="togglePasswordVisibility('password', 'eye-icon-pass')" 
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg id="eye-icon-pass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
                </div>

                <!-- Konfirmasi Kata Sandi -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password_confirmation" class="text-xs font-semibold text-gray-700">Konfirmasi kata sandi</label>
                        <span class="text-[11px] text-gray-400">Minimal 8 karakter</span>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="password_confirmation" 
                               type="password" 
                               name="password_confirmation" 
                               required 
                               autocomplete="new-password" 
                               placeholder="Amb4tuk4m"
                               class="w-full pl-10 pr-10 py-2 text-xs border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-800 focus:border-blue-800" />
                        <button type="button" 
                                onclick="togglePasswordVisibility('password_confirmation', 'eye-icon-confirm')" 
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg id="eye-icon-confirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs" />
                </div>

                <!-- Checkbox Syarat & Ketentuan -->
                <div class="pt-1">
                    <label for="terms" class="flex items-start text-xs text-gray-600 gap-2 cursor-pointer">
                        <input id="terms" 
                               type="checkbox" 
                               name="terms" 
                               required 
                               class="mt-0.5 rounded border-gray-300 text-blue-800 shadow-sm focus:ring-blue-800">
                        <span class="leading-relaxed">
                            saya setuju dengan <a href="#" class="text-red-600 font-semibold hover:underline">Syarat &amp; Ketentuan</a> serta <a href="#" class="text-red-600 font-semibold hover:underline">Kebijakan Privasi</a> FIKOM PRELOVED
                        </span>
                    </label>
                </div>

                <!-- Tombol Submit -->
                <div class="pt-2">
                    <button type="submit" class="w-full bg-[#1e40af] hover:bg-blue-900 text-white font-semibold py-2.5 rounded-full transition duration-200 text-sm shadow">
                        {{ __('Daftar Akun Kampus') }}
                    </button>
                </div>
            </form>

            <!-- SEPARATOR -->
            <div class="relative my-5 text-center">
                <hr class="border-gray-200">
                <span class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white px-3 text-xs text-gray-400">atau</span>
            </div>

            <!-- LINK LOGIN -->
            <div class="text-center text-xs space-y-1">
                <p class="text-gray-500">Sudah Punya akun Kampus?</p>
                <a href="{{ route('login') }}" class="block font-bold text-red-600 hover:underline text-sm">
                    Masuk di sini!
                </a>
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
