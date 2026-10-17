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
                <h1 class="text-base sm:text-lg font-bold tracking-tight text-gray-900 flex items-center justify-center gap-1.5">
                    Lupa Kata Sandi ? <span>🔑</span>
                </h1>
                <p class="text-xs text-gray-500 mt-1 max-w-xs mx-auto leading-relaxed">
                    Masukkan email kamu.kami akan mengirimkan tautan untuk mengatur ulang kata sandi
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- FORM FORGOT PASSWORD -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <!-- Alamat Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-700 mb-1">Alamat Email</label>
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
                               placeholder="farispratama@mhs.udb.ac.id"
                               class="w-full pl-10 pr-3 py-2 text-xs border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-800 focus:border-blue-800" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
                </div>

                <!-- Tombol Submit -->
                <div class="pt-2">
                    <button type="submit" class="w-full bg-[#1e40af] hover:bg-blue-900 text-white font-semibold py-2.5 rounded-full transition duration-200 text-sm shadow">
                        {{ __('Kirim Tautan Reset') }}
                    </button>
                </div>
            </form>

            <!-- SEPARATOR -->
            <div class="relative my-6 text-center">
                <hr class="border-gray-200">
                <span class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white px-3 text-xs text-gray-400">atau</span>
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
</x-guest-layout>
