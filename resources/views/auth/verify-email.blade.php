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
                <h1 class="text-base sm:text-lg font-bold tracking-tight text-gray-900 uppercase">
                    VERIFIKASI EMAIL
                </h1>
                <p class="text-xs text-gray-500 mt-1 max-w-xs mx-auto leading-relaxed">
                    Terima kasih telah mendaftar! Silakan klik tautan verifikasi yang baru saja kami kirimkan ke email kampus Anda.
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 font-medium text-xs text-green-600 bg-green-50 p-3 rounded-xl border border-green-200">
                    Tautan verifikasi baru telah dikirimkan ke alamat email yang Anda berikan saat pendaftaran.
                </div>
            @endif

            <div class="mt-4 flex flex-col gap-3">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="w-full bg-[#1e40af] hover:bg-blue-900 text-white font-semibold py-2.5 rounded-full transition duration-200 text-sm shadow">
                        Kirim Ulang Email Verifikasi
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="text-center">
                    @csrf
                    <button type="submit" class="text-xs font-semibold text-gray-500 hover:text-red-600 transition underline">
                        Keluar (Log Out)
                    </button>
                </form>
            </div>
        </div>

        <!-- FOOTER CARD -->
        <div class="py-3 text-center border-t border-gray-100 bg-gray-50 text-[10px] text-gray-400">
            &copy; 2026 FIKOM PRELOVED &mdash; Universitas Duta Bangsa Surakarta
        </div>
    </div>
</x-guest-layout>
