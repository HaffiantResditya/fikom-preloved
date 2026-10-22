<x-app-layout>
    <x-slot name="title">{{ $product->title }}</x-slot>

    <div class="relative min-h-[calc(100vh-4rem)] overflow-hidden">
        {{-- Decorative Brand Background Arcs & Circles (Sesuai Desain Tema) --}}
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-[#e0f2fe]/60 rounded-full blur-3xl pointer-events-none">
        </div>
        <div class="absolute -top-16 -right-16 w-80 h-80 bg-[#fee2e2]/50 rounded-full blur-3xl pointer-events-none">
        </div>
        <div
            class="absolute -bottom-28 -right-28 w-[450px] h-[450px] bg-[#ffe4e6]/40 rounded-full blur-3xl pointer-events-none">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 relative z-10">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 mb-3">
                <a href="{{ route('home') }}" class="hover:text-slate-800 transition">Beranda</a>
                <span>/</span>
                <a href="{{ route('categories') }}" class="hover:text-slate-800 transition">Kategori</a>
                <span>/</span>
                <a href="{{ route('catalog', ['category' => $product->category->slug]) }}"
                    class="hover:text-slate-800 transition">
                    {{ $product->category->name }}
                </a>
                <span>/</span>
                <span class="text-slate-900 font-bold">Detail Produk</span>
            </nav>

            {{-- Tombol Kembali --}}
            <div class="mb-6">
                <a href="{{ route('catalog', ['category' => $product->category->slug]) }}"
                    class="inline-flex items-center gap-2 bg-white border border-slate-200/90 text-slate-800 text-xs sm:text-sm font-semibold px-4 py-2 rounded-full shadow-xs hover:bg-slate-50 hover:border-slate-300 transition">
                    <svg class="w-4 h-4 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke {{ $product->category->name }}
                </a>
            </div>

            @php
                $allImages = $product->images;
                $galleryItems = [];

                if ($allImages->isNotEmpty()) {
                    foreach ($allImages as $idx => $img) {
                        $galleryItems[] = [
                            'url' => $img->url,
                            'title' => 'Foto ' . ($idx + 1),
                        ];
                    }
                }

                // Jika foto kurang dari 4, lengkapi hingga 4 slot thumbnail persis di desain mockup
                if (empty($galleryItems)) {
                    $defaultUrl = asset('images/hero.svg');
                    $galleryItems = [
                        ['url' => $defaultUrl, 'title' => 'Tampak Utama'],
                        ['url' => $defaultUrl, 'title' => 'Tampak Samping'],
                        ['url' => $defaultUrl, 'title' => 'Tampak Keyboard'],
                        ['url' => $defaultUrl, 'title' => 'Tampak Belakang'],
                    ];
                } elseif (count($galleryItems) < 4) {
                    $baseImg = $galleryItems[0]['url'];
                    while (count($galleryItems) < 4) {
                        $galleryItems[] = [
                            'url' => $baseImg,
                            'title' => 'Sudut ' . (count($galleryItems) + 1),
                        ];
                    }
                }
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-14 items-start" x-data="{
                     activeImg: '{{ $galleryItems[0]['url'] }}',
                     activeIndex: 0,
                     isWishlisted: false,
                     showWaModal: false
                 }">

                {{-- Kolom Kiri: Galeri Foto Produk --}}
                <div class="lg:col-span-6 flex flex-col">
                    {{-- Main Product Image Container --}}
                    <div
                        class="relative bg-[#e8f3fc] rounded-3xl aspect-[1.12/1] sm:aspect-square flex items-center justify-center p-8 border border-slate-200/50 shadow-xs overflow-hidden">
                        {{-- Wishlist Floating Button --}}
                        <button type="button" @click="isWishlisted = !isWishlisted"
                            class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/95 shadow-sm border border-slate-200/80 flex items-center justify-center text-slate-700 hover:text-red-500 hover:scale-105 transition z-10"
                            title="Tambah ke Wishlist">
                            <svg class="w-5 h-5 transition-colors"
                                :class="isWishlisted ? 'fill-red-500 text-red-500 stroke-red-500' : 'fill-none stroke-current'"
                                viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>
                        </button>

                        <template x-if="activeImg">
                            <img :src="activeImg" alt="{{ $product->title }}"
                                class="max-h-full max-w-full object-contain drop-shadow-sm transition-all duration-200"
                                :style="activeIndex === 1 ? 'transform: scale(0.96);' : (activeIndex === 2 ? 'transform: scale(1.03);' : (activeIndex === 3 ? 'transform: scale(0.98);' : ''))"
                                id="main-product-image">
                        </template>
                        <template x-if="!activeImg">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <svg class="w-28 h-28 opacity-40" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <rect x="2" y="3" width="20" height="14" rx="2" stroke-width="1.5" />
                                    <line x1="8" y1="21" x2="16" y2="21" stroke-width="1.5" />
                                    <line x1="12" y1="17" x2="12" y2="21" stroke-width="1.5" />
                                </svg>
                            </div>
                        </template>
                    </div>

                    {{-- Row Thumbnail Foto: Ukuran kecil & proporsional sesuai gambar mockup --}}
                    <div class="flex items-center gap-3 sm:gap-3.5 mt-4">
                        @foreach($galleryItems as $index => $item)
                            <button type="button" @click="activeImg = '{{ $item['url'] }}'; activeIndex = {{ $index }}"
                                class="w-16 h-16 sm:w-[72px] sm:h-[72px] rounded-2xl bg-[#e8f3fc] p-2 flex items-center justify-center border-2 transition-all duration-150 overflow-hidden cursor-pointer flex-shrink-0"
                                :class="activeIndex === {{ $index }} ? 'border-slate-800 ring-1 ring-slate-800 shadow-sm' : 'border-slate-200/90 hover:border-slate-400 opacity-80 hover:opacity-100'">
                                <img src="{{ $item['url'] }}" alt="{{ $item['title'] }}"
                                    class="max-h-full max-w-full object-contain pointer-events-none transition-transform duration-200"
                                    :class="activeIndex === {{ $index }} ? 'scale-105' : ''">
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Kolom Kanan: Detail Informasi Barang --}}
                <div class="lg:col-span-6 flex flex-col pt-1">
                    {{-- Judul Produk --}}
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        {{ $product->title }}
                    </h1>

                    {{-- Badge Kondisi --}}
                    @php
                        $cLabel = $product->condition_label;
                        if ($cLabel === 'Seperti Baru') {
                            $badgeClass = 'bg-emerald-50 text-emerald-700 border border-emerald-200/80';
                        } elseif ($cLabel === 'Layak Pakai') {
                            $badgeClass = 'bg-slate-100 text-slate-700 border border-slate-200/80';
                        } else {
                            $badgeClass = 'bg-amber-50 text-amber-700 border border-amber-200/80';
                        }
                    @endphp
                    <div class="mt-2.5">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">
                            {{ $cLabel }}
                        </span>
                    </div>

                    {{-- Harga Produk --}}
                    <div class="text-3xl sm:text-4xl font-black text-slate-900 mt-3 tracking-tight">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </div>

                    {{-- Lokasi & Penjual --}}
                    <div class="mt-4 space-y-2 text-xs sm:text-sm text-slate-600">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ $product->location }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Penjual: {{ $product->seller->name }}</span>
                        </div>
                    </div>

                    <div class="border-t border-slate-200/80 my-5"></div>

                    {{-- Deskripsi Produk --}}
                    <div>
                        <h2 class="text-sm sm:text-base font-bold text-slate-900 mb-1.5">Deskripsi Produk</h2>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                            {{ $product->description }}</p>
                    </div>

                    {{-- Lokasi COD --}}
                    <div class="mt-5">
                        <h2 class="text-sm sm:text-base font-bold text-slate-900 mb-1.5">Lokasi COD</h2>
                        <div class="flex items-center gap-2 text-xs sm:text-sm text-slate-600">
                            <svg class="w-4 h-4 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ $product->cod_area }}</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Dijual oleh</p>
                            <p class="font-semibold text-gray-800">{{ $product->seller->name }}</p>
                        </div>
                    </div>


                    {{-- Informasi Tambahan --}}
                    <div class="mt-6">
                        <h2 class="text-sm sm:text-base font-bold text-slate-900 mb-3">Informasi Tambahan</h2>
                        <div class="space-y-2 text-xs sm:text-sm text-slate-600">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Produk tersedia</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Harga dapat didiskusikan langsung dengan penjual</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Transaksi dilakukan langsung antara pembeli dan penjual</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>