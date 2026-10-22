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
            @if($product->status !== 'approved' && Auth::check() && Auth::id() === $product->seller_id)
                <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 mb-3">
                    <a href="{{ route('home') }}" class="hover:text-slate-800 transition">Beranda</a>
                    <span>/</span>
                    <a href="{{ route('profile.show') }}" class="hover:text-slate-800 transition">Profil Saya</a>
                    <span>/</span>
                    <span class="text-slate-900 font-bold">
                        Detail Barang ({{ $product->status === 'rejected' ? 'Ditolak' : 'Menunggu Review' }})
                    </span>
                </nav>
            @else
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
            @endif

<<<<<<< HEAD
            {{-- Tombol Kembali --}}
            <div class="mb-6">
                @if($product->status !== 'approved' && Auth::check() && Auth::id() === $product->seller_id)
                    <a href="{{ route('profile.show') }}"
                        class="inline-flex items-center gap-2 bg-white border border-slate-200/90 text-slate-800 text-xs sm:text-sm font-semibold px-4 py-2 rounded-full shadow-xs hover:bg-slate-50 hover:border-slate-300 transition">
                        <svg class="w-4 h-4 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Profile
                    </a>
                @else
                    <a href="{{ route('catalog', ['category' => $product->category->slug]) }}"
                        class="inline-flex items-center gap-2 bg-white border border-slate-200/90 text-slate-800 text-xs sm:text-sm font-semibold px-4 py-2 rounded-full shadow-xs hover:bg-slate-50 hover:border-slate-300 transition">
                        <svg class="w-4 h-4 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke {{ $product->category->name }}
                    </a>
                @endif
            </div>

            {{-- Banner Status Khusus untuk Produk Ditolak --}}
            @if($product->status === 'rejected')
                <div class="mb-8 rounded-3xl bg-gradient-to-br from-red-50 via-rose-50 to-orange-50 border border-red-200/90 p-5 sm:p-7 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-red-100 border border-red-200 text-red-600 flex items-center justify-center flex-shrink-0 shadow-xs mt-0.5">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-600 text-white shadow-xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Iklan Ditolak oleh Moderator
                                </span>
                                <span class="text-xs text-red-700 font-medium">
                                    Ditinjau pada: {{ $product->updated_at->translatedFormat('d F Y, H:i') ?? $product->updated_at->format('d/m/Y H:i') }} WIB
                                </span>
                            </div>
                            <h3 class="text-base sm:text-lg font-bold text-red-950 mt-2">
                                Iklan ini tidak tayang di katalog publik
                            </h3>
                            <p class="text-xs sm:text-sm text-red-800/90 mt-1 max-w-3xl leading-relaxed">
                                Barang ini ditolak saat tahap moderasi kurasi. Anda dapat meninjau rincian barang yang telah diunggah di bawah ini dan membaca alasan penolakan dari admin untuk melakukan perbaikan.
                            </p>

                            {{-- Kotak Alasan Penolakan --}}
                            <div class="mt-4 bg-white/95 border border-red-200/90 rounded-2xl p-4 sm:p-5 shadow-2xs">
                                <div class="flex items-center gap-2 text-xs font-bold text-red-900 mb-1.5">
                                    <svg class="w-4 h-4 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Catatan / Alasan Penolakan:
                                </div>
                                <p class="text-xs sm:text-sm text-red-900 font-semibold leading-relaxed bg-red-50/50 p-3.5 rounded-xl border border-red-100">
                                    {{ $product->rejection_reason ?: 'Foto produk tidak memenuhi standar atau informasi barang belum lengkap.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($product->status === 'pending')
                {{-- Banner Status Produk Menunggu --}}
                <div class="mb-8 rounded-3xl bg-amber-50 border border-amber-200 p-5 sm:p-6 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-100 border border-amber-200 text-amber-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-500 text-white">
                                Menunggu Verifikasi Admin
                            </span>
                            <h3 class="text-base sm:text-lg font-bold text-amber-950 mt-1.5">
                                Iklan ini sedang dalam antrean moderasi kurator
                            </h3>
                            <p class="text-xs sm:text-sm text-amber-800 mt-1">
                                Mohon tunggu beberapa saat. Barang akan otomatis tayang di katalog publik setelah disetujui.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

=======
>>>>>>> 9f06ffaca7ece7e616741af3e9b06a081a60808b
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

                    {{-- Badge Kondisi & Status --}}
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
                    <div class="mt-2.5 flex items-center gap-2 flex-wrap">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">
                            {{ $cLabel }}
                        </span>

                        @if($product->status === 'rejected')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> Ditolak
                            </span>
                        @elseif($product->status === 'pending')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span> Menunggu Review
                            </span>
                        @elseif($product->status === 'sold')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span> Terjual
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> Aktif
                            </span>
                        @endif
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
<<<<<<< HEAD
                            <span>{{ $product->cod_area }}</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Penjual bersedia melakukan COD di area yang telah disepakati.</p>
=======
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
>>>>>>> 9f06ffaca7ece7e616741af3e9b06a081a60808b
                    </div>

                    {{-- Tombol Aksi Sesuai Status --}}
                    @if($product->status === 'approved')
                        <div class="flex items-center gap-3 mt-6">
                            <button type="button" @click="showWaModal = true" id="whatsapp-contact-btn"
                                class="flex-1 inline-flex items-center justify-center gap-2.5 bg-[#16a34a] hover:bg-[#15803d] text-white font-bold py-3.5 px-6 rounded-full shadow-sm hover:shadow transition text-xs sm:text-sm cursor-pointer">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                </svg>
                                Chat Penjual via WhatsApp
                            </button>

                            <button type="button" @click="isWishlisted = !isWishlisted"
                                class="w-14 sm:w-16 h-12 rounded-full border border-slate-300 bg-white hover:bg-slate-50 flex items-center justify-center text-slate-700 hover:text-red-500 transition shadow-xs flex-shrink-0"
                                title="Simpan ke Wishlist">
                                <svg class="w-5 h-5 transition-colors"
                                    :class="isWishlisted ? 'fill-red-500 text-red-500 stroke-red-500' : 'fill-none stroke-current'"
                                    viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                            </button>
                        </div>
                    @elseif($product->status === 'rejected')
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 mt-6">
                            {{-- 1 Tombol Perbaiki / Pasang Ulang --}}
                            <a href="{{ route('products.create', [
                                'title' => $product->title,
                                'category_id' => $product->category_id,
                                'condition' => $product->condition,
                                'price' => $product->price,
                                'description' => $product->description,
                                'location' => $product->location,
                                'cod_area' => $product->cod_area,
                            ]) }}"
                               class="flex-1 inline-flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white font-bold py-3.5 px-6 rounded-full shadow-sm hover:shadow transition text-xs sm:text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Perbaiki / Pasang Ulang
                            </a>

                            {{-- 1 Tombol Pasang Iklan --}}
                            <a href="{{ route('products.create') }}"
                               class="inline-flex items-center justify-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-800 font-bold py-3.5 px-6 rounded-full shadow-xs transition text-xs sm:text-sm">
                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Pasang Iklan
                            </a>
                        </div>
                    @endif

                    {{-- Pop-up Modal Konfirmasi WhatsApp (Persis Seperti Mockup) --}}
                    @if($product->status === 'approved')
                        <div x-show="showWaModal"
                             x-cloak
                             class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 sm:p-6"
                             role="dialog"
                             aria-modal="true">

                            {{-- Backdrop --}}
                            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity"
                                 @click="showWaModal = false"></div>

                            {{-- Modal Box --}}
                            <div class="relative bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-100 z-10 transition-all transform scale-100"
                                 @click.outside="showWaModal = false">

                                {{-- Tombol Silang (Close) --}}
                                <button type="button" @click="showWaModal = false"
                                        class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition">
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
                    @endif

                    {{-- Informasi Tambahan --}}
                    <div class="mt-6">
                        <h2 class="text-sm sm:text-base font-bold text-slate-900 mb-3">Informasi Tambahan</h2>
                        <div class="space-y-2 text-xs sm:text-sm text-slate-600">
<<<<<<< HEAD
                            @if($product->status === 'rejected')
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-red-700 font-medium">Status iklan ditolak dan tidak tampil di pencarian atau katalog publik</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <span>Hanya Anda (penjual) dan administrator yang dapat mengakses halaman detail ini</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Silakan perbaiki foto atau rincian deskripsi sesuai instruksi kurasi lalu ajukan kembali</span>
                                </div>
                            @elseif($product->status === 'pending')
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-amber-700 font-medium">Iklan sedang dalam proses verifikasi tim kurasi</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Iklan akan langsung aktif setelah disetujui oleh admin</span>
                                </div>
                            @else
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Produk tersedia</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Harga dapat didiskusikan langsung dengan penjual</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Transaksi dilakukan langsung antara pembeli dan penjual</span>
                                </div>
                            @endif
=======
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
>>>>>>> 9f06ffaca7ece7e616741af3e9b06a081a60808b
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