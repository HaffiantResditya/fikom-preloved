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
                     showWaModal: false
                 }">

                {{-- Kolom Kiri: Galeri Foto Produk --}}
                <div class="lg:col-span-6 flex flex-col">
                    {{-- Main Product Image Container --}}
                    <div
                        class="relative bg-[#e8f3fc] rounded-3xl aspect-[1.12/1] sm:aspect-square flex items-center justify-center p-8 border border-slate-200/50 shadow-xs overflow-hidden">


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
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
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
                            <span>{{ $product->cod_area ?: $product->location }}</span>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1.5">Penjual bersedia melakukan COD di area yang telah disepakati.</p>
                    </div>

                    {{-- Tombol Aksi Chat WhatsApp --}}
                    <div class="mt-6">
                        <button type="button" @click="showWaModal = true"
                            class="w-full inline-flex items-center justify-center gap-2.5 bg-[#16a34a] hover:bg-[#15803d] text-white font-semibold py-3.5 px-6 rounded-full shadow-xs hover:shadow transition-all duration-200 text-sm sm:text-base cursor-pointer">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 0012 20.25z" />
                            </svg>
                            <span>Chat Penjual via WhatsApp</span>
                        </button>
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

            {{-- Modal Konfirmasi WhatsApp --}}
            <div x-show="showWaModal" x-cloak
                class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @keydown.escape.window="showWaModal = false">

                {{-- Modal Card --}}
                <div @click.away="showWaModal = false"
                    x-show="showWaModal"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100 text-left">

                    {{-- Tombol Tutup (X) --}}
                    <button type="button" @click="showWaModal = false"
                        class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition p-1 rounded-lg hover:bg-slate-100 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    {{-- Header Modal --}}
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0">
                            {{-- WhatsApp Icon --}}
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.969.584 1.761.815 2.796.815 3.183 0 5.769-2.587 5.77-5.766.001-3.181-2.585-5.768-5.77-5.768zm0-2c4.291 0 7.769 3.477 7.77 7.768 0 4.29-3.478 7.767-7.77 7.767-1.391 0-2.693-.367-3.821-1.01l-4.57 1.198 1.22-4.455c-.754-1.189-1.18-2.584-1.179-4.068 0-4.29 3.478-7.768 7.77-7.768z" />
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900">Lanjutkan ke WhatsApp?</h3>
                    </div>

                    {{-- Deskripsi --}}
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed mb-4">
                        Kamu akan diarahkan ke WhatsApp untuk menghubungi penjual dan mendiskusikan detail barang serta lokasi COD.
                    </p>

                    {{-- Snippet Produk --}}
                    <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3 flex items-center gap-3 mb-5">
                        <div class="w-14 h-14 rounded-lg bg-white border border-slate-200/60 flex items-center justify-center overflow-hidden flex-shrink-0 p-1">
                            @php
                                $thumbUrl = $product->primaryImage?->url ?? ($product->images?->first()?->url ?? null);
                            @endphp
                            @if($thumbUrl)
                                <img src="{{ $thumbUrl }}" alt="{{ $product->title }}" class="max-h-full max-w-full object-contain">
                            @else
                                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate">{{ $product->title }}</h4>
                            <div class="text-xs sm:text-sm font-extrabold text-slate-900 mt-0.5">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">{{ $product->seller->name }}</div>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center gap-3">
                        <button type="button" @click="showWaModal = false"
                            class="flex-1 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold py-2.5 px-4 rounded-xl text-xs sm:text-sm transition text-center shadow-xs cursor-pointer">
                            Batal
                        </button>
                        <a href="{{ $product->getWhatsappLink() }}" target="_blank" rel="noopener noreferrer"
                            @click="showWaModal = false"
                            class="flex-[2] inline-flex items-center justify-center gap-2 bg-[#16a34a] hover:bg-[#15803d] text-white font-semibold py-2.5 px-4 rounded-xl text-xs sm:text-sm transition text-center shadow-xs cursor-pointer">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.969.584 1.761.815 2.796.815 3.183 0 5.769-2.587 5.77-5.766.001-3.181-2.585-5.768-5.77-5.768zm0-2c4.291 0 7.769 3.477 7.77 7.768 0 4.29-3.478 7.767-7.77 7.767-1.391 0-2.693-.367-3.821-1.01l-4.57 1.198 1.22-4.455c-.754-1.189-1.18-2.584-1.179-4.068 0-4.29 3.478-7.768 7.77-7.768z" />
                            </svg>
                            <span>Lanjut ke WhatsApp</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>