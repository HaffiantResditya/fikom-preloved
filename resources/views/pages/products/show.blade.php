<x-app-layout>
    <x-slot name="title">{{ $product->title }}</x-slot>

    <div class="relative min-h-[calc(100vh-4rem)] overflow-hidden">
        {{-- Decorative Brand Background Arcs & Circles (Sesuai Desain Tema) --}}
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-[#e0f2fe]/60 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -top-16 -right-16 w-80 h-80 bg-[#fee2e2]/50 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-28 -right-28 w-[450px] h-[450px] bg-[#ffe4e6]/40 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 relative z-10">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 mb-3">
                <a href="{{ route('home') }}" class="hover:text-slate-800 transition">Beranda</a>
                <span>/</span>
                <a href="{{ route('categories') }}" class="hover:text-slate-800 transition">Kategori</a>
                <span>/</span>
                <a href="{{ route('catalog', ['category' => $product->category->slug]) }}" class="hover:text-slate-800 transition">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
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

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-14 items-start"
                 x-data="{
                     activeImg: '{{ $galleryItems[0]['url'] }}',
                     activeIndex: 0,
                     isWishlisted: false,
                     showWaModal: false
                 }">

                {{-- Kolom Kiri: Galeri Foto Produk --}}
                <div class="lg:col-span-6 flex flex-col">
                    {{-- Main Product Image Container --}}
                    <div class="relative bg-[#e8f3fc] rounded-3xl aspect-[1.12/1] sm:aspect-square flex items-center justify-center p-8 border border-slate-200/50 shadow-xs overflow-hidden">
                        {{-- Wishlist Floating Button --}}
                        <button type="button" @click="isWishlisted = !isWishlisted"
                                class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/95 shadow-sm border border-slate-200/80 flex items-center justify-center text-slate-700 hover:text-red-500 hover:scale-105 transition z-10"
                                title="Tambah ke Wishlist">
                            <svg class="w-5 h-5 transition-colors"
                                 :class="isWishlisted ? 'fill-red-500 text-red-500 stroke-red-500' : 'fill-none stroke-current'"
                                 viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
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
                                    <rect x="2" y="3" width="20" height="14" rx="2" stroke-width="1.5"/>
                                    <line x1="8" y1="21" x2="16" y2="21" stroke-width="1.5"/>
                                    <line x1="12" y1="17" x2="12" y2="21" stroke-width="1.5"/>
                                </svg>
                            </div>
                        </template>
                    </div>

                    {{-- Row Thumbnail Foto: Ukuran kecil & proporsional sesuai gambar mockup --}}
                    <div class="flex items-center gap-3 sm:gap-3.5 mt-4">
                        @foreach($galleryItems as $index => $item)
                            <button type="button"
                                    @click="activeImg = '{{ $item['url'] }}'; activeIndex = {{ $index }}"
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
                    <div class="mt-2.5">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#fef3c7] text-[#d97706]">
                            {{ $product->condition_label }}
                        </span>
                    </div>

                    {{-- Harga Produk --}}
                    <div class="text-3xl sm:text-4xl font-black text-slate-900 mt-3 tracking-tight">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </div>

                    {{-- Lokasi & Penjual --}}
                    <div class="mt-4 space-y-2 text-xs sm:text-sm text-slate-600">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ $product->location }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Penjual: {{ $product->seller->name }}</span>
                        </div>
                    </div>

                    <div class="border-t border-slate-200/80 my-5"></div>

                    {{-- Deskripsi Produk --}}
                    <div>
                        <h2 class="text-sm sm:text-base font-bold text-slate-900 mb-1.5">Deskripsi Produk</h2>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed whitespace-pre-line">{{ $product->description }}</p>
                    </div>

                    {{-- Lokasi COD --}}
                    <div class="mt-5">
                        <h2 class="text-sm sm:text-base font-bold text-slate-900 mb-1.5">Lokasi COD</h2>
                        <div class="flex items-center gap-2 text-xs sm:text-sm text-slate-600">
                            <svg class="w-4 h-4 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ $product->cod_area }}</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Penjual bersedia melakukan COD di area yang telah disepakati.</p>
                    </div>

                    {{-- Tombol Aksi: Chat WA & Wishlist --}}
                    <div class="flex items-center gap-3 mt-6">
                        <button type="button" @click="showWaModal = true" id="whatsapp-contact-btn"
                                class="flex-1 inline-flex items-center justify-center gap-2.5 bg-[#16a34a] hover:bg-[#15803d] text-white font-bold py-3.5 px-6 rounded-full shadow-sm hover:shadow transition text-xs sm:text-sm cursor-pointer">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                            Chat Penjual via WhatsApp
                        </button>

                        {{-- Wishlist Button --}}
                        <button type="button" @click="isWishlisted = !isWishlisted"
                                class="w-14 sm:w-16 h-12 rounded-full border border-slate-300 bg-white hover:bg-slate-50 flex items-center justify-center text-slate-700 hover:text-red-500 transition shadow-xs flex-shrink-0"
                                title="Simpan ke Wishlist">
                            <svg class="w-5 h-5 transition-colors"
                                 :class="isWishlisted ? 'fill-red-500 text-red-500 stroke-red-500' : 'fill-none stroke-current'"
                                 viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>
                        </button>
                    </div>

                    {{-- Pop-up Modal Konfirmasi WhatsApp (Persis Seperti Mockup) --}}
                    <div x-show="showWaModal"
                         x-cloak
                         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 sm:p-6"
                         role="dialog"
                         aria-modal="true">

                        {{-- Backdrop --}}
                        <div x-show="showWaModal"
                             x-transition:enter="ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             @click="showWaModal = false"
                             class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity"></div>

                        {{-- Dialog Card --}}
                        <div x-show="showWaModal"
                             x-transition:enter="ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave="ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             class="relative bg-white rounded-3xl p-6 sm:p-7 shadow-2xl max-w-md w-full border border-slate-100 z-10">

                            {{-- Tombol Close (✕) --}}
                            <button type="button" @click="showWaModal = false"
                                    class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 transition p-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>

                            {{-- Header Modal: Icon WA & Judul --}}
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0">
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                    </svg>
                                </div>
                                <h3 class="text-base sm:text-lg font-bold text-slate-900">
                                    Lanjutkan ke WhatsApp?
                                </h3>
                            </div>

                            {{-- Subtitle / Keterangan --}}
                            <p class="text-xs sm:text-sm text-slate-500 mt-2.5 mb-4 leading-relaxed">
                                Kamu akan diarahkan ke WhatsApp untuk menghubungi penjual dan mendiskusikan detail barang serta lokasi COD.
                            </p>

                            {{-- Mini Preview Card Produk --}}
                            <div class="border border-slate-200/90 rounded-2xl p-3 bg-slate-50/70 flex items-center gap-3.5 my-4">
                                <div class="w-14 h-14 rounded-xl bg-[#e8f3fc] overflow-hidden flex items-center justify-center border border-slate-100 p-1 flex-shrink-0">
                                    <template x-if="activeImg">
                                        <img :src="activeImg" alt="{{ $product->title }}" class="max-h-full max-w-full object-contain">
                                    </template>
                                    <template x-if="!activeImg">
                                        <svg class="w-8 h-8 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <rect x="2" y="3" width="20" height="14" rx="2" stroke-width="1.5"/>
                                        </svg>
                                    </template>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate">{{ $product->title }}</h4>
                                    <p class="text-xs sm:text-sm font-bold text-slate-800 mt-0.5">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $product->seller->name }}</p>
                                </div>
                            </div>

                            {{-- Tombol Batal & Lanjut ke WhatsApp --}}
                            <div class="flex items-center gap-3 mt-5">
                                <button type="button" @click="showWaModal = false"
                                        class="px-6 py-2.5 rounded-full border border-slate-200 text-slate-700 font-semibold text-xs sm:text-sm hover:bg-slate-50 transition">
                                    Batal
                                </button>
                                <a href="{{ $product->getWhatsappLink() }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   @click="showWaModal = false"
                                   class="flex-1 inline-flex items-center justify-center gap-2 bg-[#22c55e] hover:bg-[#16a34a] text-white font-bold py-2.5 px-5 rounded-full shadow-sm hover:shadow transition text-xs sm:text-sm">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                    </svg>
                                    Lanjut ke WhatsApp
                                </a>
                            </div>

                        </div>
                    </div>


                    {{-- Informasi Tambahan --}}
                    <div class="mt-6">
                        <h2 class="text-sm sm:text-base font-bold text-slate-900 mb-3">Informasi Tambahan</h2>
                        <div class="space-y-2 text-xs sm:text-sm text-slate-600">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Produk tersedia</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Harga dapat didiskusikan langsung dengan penjual</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
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

