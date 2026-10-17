<x-app-layout>
    <x-slot name="title">Semua Kategori</x-slot>

    <div class="relative min-h-[calc(100vh-140px)] py-8 sm:py-10">
        {{-- Soft pastel ambient blur effects --}}
        <div class="absolute top-0 left-0 w-80 h-80 bg-blue-100/40 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-20 right-0 w-80 h-80 bg-rose-100/40 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Breadcrumb --}}
            <nav class="text-xs text-gray-500 mb-4 flex items-center gap-1.5">
                <a href="{{ route('home') }}" class="hover:text-gray-800 transition">Beranda</a>
                <span>/</span>
                <span class="font-semibold text-gray-800">Kategori</span>
            </nav>

            {{-- Header Box --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 flex flex-col md:flex-row md:items-center md:justify-between gap-5 shadow-sm">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="w-1 h-3.5 bg-gray-400 rounded-full inline-block"></span>
                        <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kategori Akses Cepat</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-1.5 tracking-tight">
                        Semua Kategori
                    </h1>
                    <p class="text-gray-500 text-xs sm:text-sm mt-1 max-w-2xl leading-relaxed">
                        Pilih kategori untuk menemukan barang bekas berkualitas dari sesama mahasiswa FIKOM.
                    </p>
                </div>
                <div>
                    <a href="{{ route('home') }}"
                       class="inline-flex items-center gap-1.5 bg-white border border-gray-300 hover:border-gray-400 hover:bg-gray-50 text-gray-700 text-xs sm:text-sm font-medium px-4 py-2.5 rounded-lg transition shadow-sm whitespace-nowrap">
                        ← Kembali ke Beranda
                    </a>
                </div>
            </div>

            {{-- 8 Categories Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
                @foreach($categories as $category)
                    @php
                        $meta = $category->icon_meta;
                    @endphp
                    <div class="bg-white rounded-2xl border border-gray-200/90 p-6 flex flex-col justify-between hover:border-gray-300 hover:shadow-sm transition group">
                        <div>
                            {{-- Icon Box --}}
                            <div style="{{ $meta['bg'] }}" class="w-12 h-12 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
                                {!! $meta['svg'] !!}
                            </div>
                            <h2 class="text-base font-bold text-gray-900 group-hover:text-black transition">
                                {{ $category->name }}
                            </h2>
                            <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                                {{ $category->description }}
                            </p>
                        </div>

                        {{-- Footer Badge & Link --}}
                        <div class="flex items-center justify-between mt-6 pt-3 border-t border-gray-100">
                            <span class="bg-slate-100 text-slate-600 text-xs font-medium px-2.5 py-1 rounded-md">
                                {{ $category->products_count ?? 0 }} barang
                            </span>
                            <a href="{{ route('catalog', ['category' => $category->slug]) }}"
                               class="text-xs font-bold text-gray-900 hover:text-black flex items-center gap-1 transition">
                                Lihat →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Bottom CTA Box --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-7 mt-8 flex flex-col sm:flex-row items-center justify-between gap-5 shadow-sm">
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-gray-900">
                        Barang yang kamu cari belum ada di kategori ini?
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                        Cari lewat kolom pencarian, atau mulai jual barangmu dan pilih kategori terdekat.
                    </p>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0 w-full sm:w-auto">
                    <a href="{{ route('catalog') }}"
                       class="flex-1 sm:flex-none text-center bg-neutral-900 hover:bg-neutral-800 text-white text-xs sm:text-sm font-medium px-5 py-2.5 rounded-lg transition shadow-sm">
                        Cari Barang
                    </a>
                    <a href="{{ route('products.create') }}"
                       class="flex-1 sm:flex-none text-center bg-white border border-gray-300 hover:border-gray-400 hover:bg-gray-50 text-gray-800 text-xs sm:text-sm font-medium px-5 py-2.5 rounded-lg transition shadow-sm">
                        Mulai Jual
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
