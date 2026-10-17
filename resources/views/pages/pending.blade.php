<x-app-layout>
    <x-slot name="title">Akun Menunggu Persetujuan</x-slot>

    <div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <x-heroicon-o-clock class="w-12 h-12 text-yellow-500"/>
        </div>
        <h1 class="text-3xl font-extrabold text-gray-800 mb-3">Akun Sedang Diverifikasi</h1>
        <p class="text-gray-500 mb-2 leading-relaxed">
            Halo, <strong>{{ auth()->user()->name }}</strong>! Akun Anda terdaftar sebagai mahasiswa dan
            sedang menunggu <strong>persetujuan Admin</strong>.
        </p>
        <p class="text-gray-400 text-sm mb-10">Biasanya proses verifikasi memakan waktu 1x24 jam. Anda akan dapat mengakses semua fitur setelah disetujui.</p>

        <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-6 text-left mb-8 space-y-3">
            <h3 class="font-semibold text-indigo-800 text-sm">Sementara ini, Anda bisa:</h3>
            <div class="flex items-center gap-3 text-sm text-indigo-700">
                <x-heroicon-o-eye class="w-5 h-5 flex-shrink-0"/>
                <span>Melihat katalog dan detail barang</span>
            </div>
            <div class="flex items-center gap-3 text-sm text-gray-400">
                <x-heroicon-o-lock-closed class="w-5 h-5 flex-shrink-0"/>
                <span>Menghubungi penjual & pasang iklan (tersedia setelah disetujui)</span>
            </div>
        </div>

        <a href="{{ route('catalog') }}"
           class="bg-indigo-600 text-white font-semibold px-6 py-3 rounded-xl hover:bg-indigo-700 transition inline-block">
            Lihat Katalog Barang
        </a>
    </div>
</x-app-layout>
