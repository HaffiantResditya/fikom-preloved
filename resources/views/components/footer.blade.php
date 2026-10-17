<footer class="bg-white border-t border-gray-200 mt-20 py-8 text-center text-xs text-gray-500 relative overflow-hidden">
    {{-- Soft pastel bottom background decorative curves --}}
    <div class="absolute -bottom-10 -left-10 w-48 h-48 bg-rose-100/50 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-10 -right-10 w-48 h-48 bg-sky-100/50 rounded-full blur-3xl pointer-events-none"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="text-gray-500 font-normal">
            &copy; {{ date('Y') }} FIKOM PRELOVED — Universitas Duta Bangsa Surakarta
        </p>
        <div class="flex items-center gap-6 text-gray-400 text-xs">
            <a href="{{ route('home') }}" class="hover:text-gray-600 transition">Beranda</a>
            <a href="{{ route('categories') }}" class="hover:text-gray-600 transition">Kategori</a>
            <a href="{{ route('catalog') }}" class="hover:text-gray-600 transition">Katalog</a>
        </div>
    </div>
</footer>
