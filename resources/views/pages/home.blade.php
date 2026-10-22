<x-app-layout>
    <x-slot name="title">Pasar Preloved Mahasiswa FIKOM</x-slot>

    {{-- Hero Section --}}
    <section class="relative bg-transparent overflow-hidden pt-8 pb-12 lg:pt-12 lg:pb-16 border-b border-gray-200/60">
        {{-- Pastel decorative background arcs --}}
        <div
            class="absolute -top-32 -left-32 w-[420px] h-[420px] bg-[#e0f2fe]/50 rounded-full blur-2xl pointer-events-none">
        </div>
        <div class="absolute top-2 -right-16 w-80 h-80 bg-[#fee2e2]/40 rounded-full blur-2xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                {{-- Left Text Column --}}
                <div class="lg:col-span-6 flex flex-col items-start">
                    <h1
                        class="text-3xl sm:text-4xl lg:text-[40px] font-extrabold text-slate-900 leading-[1.2] tracking-tight">
                        Pasar Preloved Anak Kos FIKOM — Jual Cepat, Beli Hemat, COD Sekitar Kampus
                    </h1>
                    <p class="text-slate-600 text-sm sm:text-base mt-4 max-w-xl leading-relaxed">
                        Temukan barang bekas berkualitas dari sesama mahasiswa FIKOM. Aman, mudah, dan bisa COD langsung
                        di area kampus.
                    </p>
                    <div class="flex items-center gap-3 mt-6 sm:mt-8">
                        <a href="{{ route('catalog') }}"
                            style="background-color: #0f172a !important; color: #ffffff !important;"
                            class="inline-flex items-center gap-2 font-medium text-xs sm:text-sm px-5 py-2.5 rounded-lg transition shadow-sm hover:opacity-90">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Cari Barang
                        </a>
                        <a href="{{ route('products.create') }}"
                            style="background-color: #ffffff !important; color: #0f172a !important; border: 1.5px solid #cbd5e1 !important;"
                            class="inline-flex items-center justify-center font-medium text-xs sm:text-sm px-5 py-2.5 rounded-lg transition shadow-sm hover:bg-slate-50">
                            Mulai Jual
                        </a>
                    </div>
                </div>

                {{-- Right Hero SVG Column --}}
                <div class="lg:col-span-6 flex justify-center lg:justify-end">
                    <div class="relative w-full max-w-lg lg:max-w-xl flex items-center justify-center">
                        <img src="{{ asset('images/hero.svg') }}" alt="FIKOM Preloved Hero"
                            style="max-height: 340px; width: 100%; height: auto;"
                            class="w-full h-auto max-h-[340px] object-contain drop-shadow-md transition-transform duration-300 hover:scale-[1.01]">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Kategori Akses Cepat --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-6">
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-2">
                <span class="w-1 h-4 bg-slate-400 rounded-full inline-block"></span>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori Akses Cepat</span>
            </div>
            <a href="{{ route('categories') }}"
                class="text-xs font-semibold text-slate-600 hover:text-black transition flex items-center gap-1">
                Lihat Semua →
            </a>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($quickCategories as $cat)
                @php
                    $iconStyle = $cat->icon_meta;
                @endphp
                <a href="{{ route('catalog', ['category' => $cat->slug]) }}"
                    class="group bg-white rounded-2xl border border-slate-200/90 p-6 flex flex-col items-center justify-center text-center hover:border-slate-300 hover:shadow-sm transition">
                    <div style="{{ $iconStyle['bg'] }}"
                        class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3 group-hover:scale-105 transition-transform">
                        {!! $iconStyle['svg'] !!}
                    </div>
                    <span class="text-xs sm:text-sm font-semibold text-slate-800 group-hover:text-indigo-600 transition">
                        {{ $cat->name }}
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Grid Produk Terbaru --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-16">
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-2">
                <span class="w-1 h-4 bg-slate-400 rounded-full inline-block"></span>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Grid Produk Terbaru</span>
            </div>
            <a href="{{ route('catalog', ['category' => 'semua']) }}"
                class="text-xs font-semibold text-slate-600 hover:text-black transition flex items-center gap-1">
                Lihat Semua →
            </a>
        </div>

        @if($featuredProducts->isEmpty())
            <div class="text-center py-16 bg-white rounded-2xl border border-slate-200/80 text-slate-400">
                <x-heroicon-o-shopping-bag class="w-14 h-14 mx-auto mb-3 opacity-30" />
                <p class="text-sm font-medium">Belum ada iklan produk yang disetujui.</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3.5 sm:gap-4">
                @foreach($featuredProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        @endif
    </section>
</x-app-layout>