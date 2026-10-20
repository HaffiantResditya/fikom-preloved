<x-guest-layout>
    <div class="w-full max-w-[430px] mx-auto">
        <!-- Card Auth Container -->
        <div class="fikom-card w-full my-0 {{ $errors->any() ? 'shake-error' : '' }}">
            <div class="p-7 sm:p-8">
                <!-- LOGO -->
                <div class="flex justify-center mb-1">
                    <img src="{{ asset('images/logo_fikompreloved.svg') }}" 
                         alt="Logo FIKOM PRELOVED" 
                         class="fikom-logo"
                         style="height: 88px; max-height: 92px; width: auto; object-fit: contain;">
                </div>

                <!-- HEADER -->
                <div class="text-center mb-5">
                    <h1 class="text-base sm:text-lg font-bold tracking-tight text-gray-900 uppercase">
                        ATUR ULANG KATA SANDI
                    </h1>
                    <p class="text-[11px] sm:text-xs text-gray-500 mt-0.5 max-w-xs mx-auto leading-relaxed">
                        Masukkan kata sandi baru untuk akun mahasiswa Anda
                    </p>
                </div>

                <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

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
                                   value="{{ old('email', $request->email) }}" 
                                   required 
                                   autofocus 
                                   autocomplete="username" 
                                   placeholder="farispratama@mhs.udb.ac.id"
                                   class="fikom-input" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
                    </div>

                    <!-- Kata Sandi Baru -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="password" class="text-xs font-semibold text-gray-700">Kata Sandi Baru</label>
                            <span id="reset-strength-text" class="text-[11px] text-gray-400">Min. 8 karakter</span>
                        </div>
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
                                   autocomplete="new-password" 
                                   placeholder="Masukkan kata sandi baru"
                                   oninput="checkResetStrength(this.value); checkResetMatch();"
                                   class="fikom-input fikom-input-password" />
                            <button type="button" 
                                    onclick="togglePasswordVisibility('password', 'eye-icon-reset-pass')" 
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                <svg id="eye-icon-reset-pass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>

                        <!-- Subtle Password Strength Bar -->
                        <div class="w-full bg-gray-100 rounded-full h-1.5 mt-1.5 overflow-hidden">
                            <div id="reset-strength-bar" class="strength-meter-bar h-full w-0 bg-gray-300 rounded-full"></div>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
                    </div>

                    <!-- Konfirmasi Kata Sandi -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="password_confirmation" class="text-xs font-semibold text-gray-700">Konfirmasi Kata Sandi Baru</label>
                            <span id="reset-match-indicator" class="text-[11px] text-gray-400">Harus sama</span>
                        </div>
                        <div class="relative flex items-center">
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
                                   placeholder="Ulangi kata sandi baru"
                                   oninput="checkResetMatch()"
                                   class="fikom-input fikom-input-password" />
                            <button type="button" 
                                    onclick="togglePasswordVisibility('password_confirmation', 'eye-icon-reset-confirm')" 
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                <svg id="eye-icon-reset-confirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs" />
                    </div>

                    <!-- Tombol Submit -->
                    <div class="pt-1">
                        <button type="submit" class="fikom-btn-primary">
                            Simpan Kata Sandi Baru
                        </button>
                    </div>
                </form>
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

    <!-- Script lokal validasi reset password -->
    <script>
        function checkResetStrength(pass) {
            const bar = document.getElementById('reset-strength-bar');
            const text = document.getElementById('reset-strength-text');
            if (!bar || !text) return;

            let strength = 0;
            if (pass.length >= 8) strength++;
            if (/[0-9]/.test(pass) && /[a-zA-Z]/.test(pass)) strength++;
            if (/[^a-zA-Z0-9]/.test(pass) || pass.length >= 12) strength++;

            if (pass.length === 0) {
                bar.style.width = '0%';
                bar.className = 'strength-meter-bar h-full bg-gray-300';
                text.innerText = 'Min. 8 karakter';
                text.className = 'text-[11px] text-gray-400';
            } else if (strength <= 1) {
                bar.style.width = '33%';
                bar.className = 'strength-meter-bar h-full bg-red-500';
                text.innerText = 'Lemah';
                text.className = 'text-[11px] text-red-500 font-medium pop-badge';
            } else if (strength === 2) {
                bar.style.width = '66%';
                bar.className = 'strength-meter-bar h-full bg-amber-500';
                text.innerText = 'Sedang';
                text.className = 'text-[11px] text-amber-600 font-medium pop-badge';
            } else {
                bar.style.width = '100%';
                bar.className = 'strength-meter-bar h-full bg-emerald-500';
                text.innerText = 'Kuat ✓';
                text.className = 'text-[11px] text-emerald-600 font-semibold pop-badge';
            }
        }

        function checkResetMatch() {
            const pass = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;
            const indicator = document.getElementById('reset-match-indicator');
            if (!indicator) return;

            if (!confirm) {
                indicator.innerText = 'Harus sama';
                indicator.className = 'text-[11px] text-gray-400';
                return;
            }

            if (pass === confirm) {
                indicator.innerText = 'Cocok ✓';
                indicator.className = 'text-[11px] text-emerald-600 font-semibold pop-badge';
            } else {
                indicator.innerText = 'Belum cocok';
                indicator.className = 'text-[11px] text-red-500 font-medium pop-badge';
            }
        }
    </script>
</x-guest-layout>
