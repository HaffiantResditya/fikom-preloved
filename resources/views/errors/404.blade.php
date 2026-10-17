<x-app-layout>
    <x-slot name="title">Halaman Tidak Ditemukan</x-slot>
    <div class="max-w-lg mx-auto px-4 py-24 text-center">
        <div class="text-8xl font-extrabold text-indigo-100 mb-4">404</div>
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Halaman Tidak Ditemukan</h1>
        <p class="text-gray-500 mb-8">Halaman yang Anda cari tidak ada atau sudah dihapus.</p>
        <a href="{{ route('home') }}" class="bg-indigo-600 text-white font-semibold px-6 py-3 rounded-xl hover:bg-indigo-700 transition">Kembali ke Beranda</a>
    </div>
</x-app-layout>
