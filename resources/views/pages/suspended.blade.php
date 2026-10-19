<x-app-layout>
    <x-slot name="title">Akun Ditangguhkan</x-slot>

    <div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
        <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6 text-red-600 shadow-sm">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>

        <span class="inline-block bg-red-50 text-red-700 text-xs font-bold px-3 py-1 rounded-full border border-red-200 mb-3">
            ⚠️ Status: Akun Dinonaktifkan
        </span>

        <h1 class="text-3xl font-extrabold text-slate-900 mb-3">Akun Anda Ditangguhkan</h1>
        <p class="text-slate-600 mb-2 leading-relaxed text-sm sm:text-base">
            Halo, <strong>{{ auth()->user()->name }}</strong>! Akses akun mahasiswa Anda ke platform FIKOM PRELOVED saat ini sedang <strong>dinonaktifkan sementara</strong> oleh Administrator.
        </p>
        <p class="text-slate-400 text-xs mb-8">
            Penangguhan dapat terjadi karena adanya laporan transaksi, dugaan pelanggaran ketentuan komunitas, atau evaluasi keamanan kampus.
        </p>

        <div class="bg-red-50 border border-red-100 rounded-2xl p-5 text-left mb-8 space-y-3">
            <h3 class="font-bold text-red-900 text-sm">Pembatasan pada Akun Anda:</h3>
            <div class="flex items-start gap-3 text-xs text-red-700">
                <span class="font-bold text-red-500">✕</span>
                <span>Tidak dapat memasang iklan baru atau mengedit barang</span>
            </div>
            <div class="flex items-start gap-3 text-xs text-red-700">
                <span class="font-bold text-red-500">✕</span>
                <span>Iklan Anda yang sedang tayang di etalase disembunyikan</span>
            </div>
            <div class="flex items-start gap-3 text-xs text-red-700">
                <span class="font-bold text-red-500">✕</span>
                <span>Akses fitur jual beli dan kontak penjual dibatasi</span>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="https://wa.me/6285727855947?text=Halo%20Admin%20FIKOM%20Preloved,%20saya%20ingin%20mengajukan%20peninjauan%20akun%20saya%20yang%20dinonaktifkan%20(Email:%20{{ urlencode(auth()->user()->email) }})"
               target="_blank"
               class="w-full sm:w-auto bg-emerald-600 text-white font-semibold px-6 py-3 rounded-xl hover:bg-emerald-700 transition flex items-center justify-center gap-2 text-sm shadow-sm">
                💬 Hubungi Admin via WhatsApp
            </a>

            <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                @csrf
                <button type="submit"
                        class="w-full bg-slate-100 text-slate-700 font-semibold px-6 py-3 rounded-xl hover:bg-slate-200 transition text-sm">
                    Keluar (Logout)
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
