<x-guest-layout>
    <div class="w-full max-w-lg mx-auto">
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
                <h1 class="text-base sm:text-lg font-bold tracking-tight text-gray-900 uppercase">BUAT AKUN KAMPUS</h1>
                <p class="text-[11px] sm:text-xs text-gray-500 mt-0.5">Satu akun untuk jual &amp; beli Preloved sesama Mahasiswa FIKOM</p>
            </div>

            <!-- FORM REGISTER -->
            <form method="POST" action="{{ route('register') }}" class="space-y-3.5" id="register-form">
                @csrf

                <!-- Baris 1: Nama Lengkap & No. Whatsapp (2 Kolom) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                        <div class="relative flex items-center">
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
                                   placeholder="Nama Lengkap"
                                   class="fikom-input" />
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs" />
                    </div>

                    <!-- No. Whatsapp -->
                    <div>
                        <label for="whatsapp_number" class="block text-xs font-semibold text-gray-700 mb-1">No. Whatsapp</label>
                        <div class="relative flex items-center">
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
                                   placeholder="081234567890"
                                   oninput="this.value = this.value.replace(/[^0-9+\s-]/g, '')"
                                   class="fikom-input" />
                        </div>
                        <x-input-error :messages="$errors->get('whatsapp_number')" class="mt-1 text-xs" />
                    </div>
                </div>

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
                               autocomplete="username" 
                               placeholder="farispratama@mhs.udb.ac.id"
                               oninput="checkRegisterEmail(this.value)"
                               class="fikom-input" />
                    </div>
                    <!-- Email Validation Message Realtime -->
                    <div id="register-email-msg" class="hidden mt-1 text-[11px] flex items-center gap-1"></div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
                </div>

                <!-- Kata Sandi -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="text-xs font-semibold text-gray-700">Kata Sandi</label>
                        <span id="strength-text" class="text-[11px] text-gray-400">Min. 8 karakter</span>
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
                               placeholder="Minimal 8 karakter"
                               oninput="checkPasswordStrength(this.value); checkPasswordMatch();"
                               class="fikom-input fikom-input-password" />
                        <button type="button" 
                                onclick="togglePasswordVisibility('password', 'eye-icon-pass')" 
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg id="eye-icon-pass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>

                    <!-- Subtle Password Strength Bar -->
                    <div class="w-full bg-gray-100 rounded-full h-1.5 mt-1.5 overflow-hidden">
                        <div id="strength-bar" class="strength-meter-bar h-full w-0 bg-gray-300 rounded-full"></div>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
                </div>

                <!-- Konfirmasi Kata Sandi -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password_confirmation" class="text-xs font-semibold text-gray-700">Konfirmasi Kata Sandi</label>
                        <span id="match-indicator" class="text-[11px] text-gray-400">Harus sama</span>
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
                               placeholder="Ulangi kata sandi"
                               oninput="checkPasswordMatch()"
                               class="fikom-input fikom-input-password" />
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
                               class="mt-0.5 rounded border-gray-300 text-[#1e4b8a] shadow-sm focus:ring-[#1e4b8a]">
                        <span class="leading-relaxed">
                            Saya setuju dengan <button type="button" onclick="openFikomModal('terms-modal')" class="text-red-600 font-semibold hover:underline inline">Syarat &amp; Ketentuan</button> serta <button type="button" onclick="openFikomModal('privacy-modal')" class="text-red-600 font-semibold hover:underline inline">Kebijakan Privasi</button> FIKOM PRELOVED
                        </span>
                    </label>
                </div>

                <!-- Tombol Submit -->
                <div class="pt-2">
                    <button type="submit" class="fikom-btn-primary">
                        Daftar Akun Kampus
                    </button>
                </div>
            </form>

            <!-- SEPARATOR -->
            <div class="fikom-divider">
                <hr>
                <span>atau</span>
            </div>

            <!-- LINK LOGIN -->
            <div class="text-center text-xs space-y-1">
                <p class="text-gray-500">Sudah punya akun Kampus?</p>
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

    <!-- MODAL SYARAT & KETENTUAN -->
    <div id="terms-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl relative text-left">
            <h3 class="text-base font-bold text-gray-900 mb-3 flex items-center gap-2">
                📜 Syarat &amp; Ketentuan FIKOM PRELOVED
            </h3>
            <div class="text-xs text-gray-600 space-y-2.5 max-h-64 overflow-y-auto pr-1 leading-relaxed">
                <p>1. <strong>Identitas Kampus:</strong> Pengguna wajib mendaftar menggunakan email resmi <code>@mhs.udb.ac.id</code> Universitas Duta Bangsa Surakarta.</p>
                <p>2. <strong>Kesesuaian Barang:</strong> Barang yang dijual harus halal, legal, dan kondisi fisik sesuai dengan deskripsi serta foto yang diunggah.</p>
                <p>3. <strong>Metode Transaksi:</strong> Diutamakan bertransaksi dengan metode COD (Cash on Delivery) di lingkungan kampus demi keamanan bersama.</p>
                <p>4. <strong>Etika Komunikasi:</strong> Penjual dan pembeli diharapkan menjaga etika komunikasi yang sopan dan saling menghargai sesama mahasiswa.</p>
            </div>
            <div class="mt-5 text-right">
                <button type="button" onclick="closeFikomModal('terms-modal')" class="px-4 py-2 bg-[#1e4b8a] text-white text-xs font-semibold rounded-full hover:bg-blue-900 transition">
                    Saya Mengerti
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL KEBIJAKAN PRIVASI -->
    <div id="privacy-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl relative text-left">
            <h3 class="text-base font-bold text-gray-900 mb-3 flex items-center gap-2">
                🔒 Kebijakan Privasi
            </h3>
            <div class="text-xs text-gray-600 space-y-2.5 max-h-64 overflow-y-auto pr-1 leading-relaxed">
                <p>1. <strong>Keamanan Data:</strong> Data pribadi seperti email kampus dan nomor WhatsApp hanya digunakan untuk verifikasi akun dan komunikasi transaksi antar mahasiswa.</p>
                <p>2. <strong>Perlindungan Kata Sandi:</strong> Kata sandi Anda dienkripsi secara aman menggunakan standar enkripsi modern (Bcrypt).</p>
                <p>3. <strong>Kerahasiaan:</strong> Kami tidak akan pernah menjual atau membagikan data pribadi Anda kepada pihak ketiga di luar ekosistem kampus FIKOM UDB.</p>
            </div>
            <div class="mt-5 text-right">
                <button type="button" onclick="closeFikomModal('privacy-modal')" class="px-4 py-2 bg-[#1e4b8a] text-white text-xs font-semibold rounded-full hover:bg-blue-900 transition">
                    Saya Mengerti
                </button>
            </div>
        </div>
    </div>

    <!-- Script Interaktif Registrasi -->
    <script>
        function checkRegisterEmail(val) {
            const msg = document.getElementById('register-email-msg');
            if (!msg) return;
            val = val.trim();
            if (!val || !val.includes('@')) {
                msg.classList.add('hidden');
                return;
            }

            msg.classList.remove('hidden');
            if (val.toLowerCase().endsWith('@mhs.udb.ac.id')) {
                msg.className = 'mt-1 text-[11px] text-emerald-600 flex items-center gap-1 pop-badge';
                msg.innerHTML = `<span>✓ Email kampus resmi terverifikasi</span>`;
            } else {
                msg.className = 'mt-1 text-[11px] text-amber-600 flex items-center gap-1 pop-badge';
                msg.innerHTML = `<span>⚠ Harus menggunakan akhiran @mhs.udb.ac.id</span>`;
            }
        }

        function checkPasswordStrength(pass) {
            const bar = document.getElementById('strength-bar');
            const text = document.getElementById('strength-text');
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

        function checkPasswordMatch() {
            const pass = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;
            const indicator = document.getElementById('match-indicator');
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
