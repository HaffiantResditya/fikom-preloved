<x-guest-layout>
    <div class="w-full max-w-[430px] mx-auto">
        <!-- Card Auth Container -->
        <div class="fikom-card w-full my-0">
            <div class="p-7 sm:p-8">
                <!-- LOGO -->
                <div class="flex justify-center mb-1">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('images/logo_fikompreloved.svg') }}" 
                             alt="Logo FIKOM PRELOVED" 
                             class="fikom-logo"
                             style="height: 88px; max-height: 92px; width: auto; object-fit: contain;">
                    </a>
                </div>

                <!-- HEADER -->
                <div class="text-center mb-5">
                    <h1 class="text-base sm:text-lg font-bold tracking-tight text-gray-900 flex items-center justify-center gap-1.5 uppercase">
                        LUPA KATA SANDI ? <span>🔑</span>
                    </h1>
                    <p class="text-[11px] sm:text-xs text-gray-500 mt-1 max-w-xs mx-auto leading-relaxed">
                        Masukkan email kampus Anda. Kami akan mengirimkan tautan untuk mengatur ulang kata sandi akun Anda.
                    </p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- FORM FORGOT PASSWORD -->
                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                    @csrf

                    <!-- Alamat Email -->
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
                                   placeholder="farispratama@mhs.udb.ac.id"
                                   oninput="checkForgotEmailHint(this.value)"
                                   class="fikom-input" />
                        </div>
                        <div id="forgot-email-hint" class="hidden mt-1 text-[11px] text-amber-600 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Gunakan email kampus resmi berakhiran @mhs.udb.ac.id</span>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
                    </div>

                    <!-- Tombol Submit -->
                    <div class="pt-1">
                        <button type="submit" class="fikom-btn-primary">
                            Kirim Tautan Reset
                        </button>
                    </div>
                </form>

                <!-- SEPARATOR -->
                <div class="fikom-divider">
                    <hr>
                    <span>atau</span>
                </div>

                <!-- LINK KEMBALI KE LOGIN -->
                <div class="text-center text-xs space-y-1">
                    <p class="text-gray-500">Sudah ingat kata sandi?</p>
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

        <!-- Navigasi Kembali ke Beranda (Tengah Halaman di Bagian Bawah) -->
        <div class="text-center mt-4">
            <a href="{{ route('home') }}" class="inline-flex items-center text-xs text-gray-500 hover:text-[#1e4b8a] transition font-medium">
                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Beranda
            </a>
        </div>
    </div>

    <script>
        function checkForgotEmailHint(val) {
            const hint = document.getElementById('forgot-email-hint');
            if (!hint) return;
            if (val.includes('@') && !val.toLowerCase().endsWith('@mhs.udb.ac.id')) {
                hint.classList.remove('hidden');
            } else {
                hint.classList.add('hidden');
            }
        }
    </script>
</x-guest-layout>
