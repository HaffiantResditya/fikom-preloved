<x-app-layout>
    <x-slot name="title">Iklan Berhasil Dikirim</x-slot>

    <div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <x-heroicon-o-check-badge class="w-12 h-12 text-green-500"/>
        </div>
        <h1 class="text-3xl font-extrabold text-gray-800 mb-3">Iklan Berhasil Dikirim!</h1>
        <p class="text-gray-500 mb-2 leading-relaxed">
            Iklan Anda sedang dalam proses <strong>review oleh Admin</strong>. Anda akan dihubungi jika ada kendala.
        </p>
        <p class="text-gray-400 text-sm mb-10">Biasanya proses review memakan waktu 1x24 jam pada hari kerja.</p>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('products.my-listings') }}"
               class="bg-indigo-600 text-white font-semibold px-6 py-3 rounded-xl hover:bg-indigo-700 transition">
                Lihat Iklan Saya
            </a>
            <a href="{{ route('products.create') }}"
               class="bg-gray-100 text-gray-700 font-semibold px-6 py-3 rounded-xl hover:bg-gray-200 transition">
                Pasang Iklan Lagi
            </a>
        </div>
    </div>
</x-app-layout>
