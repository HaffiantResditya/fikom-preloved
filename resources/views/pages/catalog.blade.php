<x-app-layout>
    <x-slot name="title">{{ $currentCategory->name ?? 'Elektronik' }} - Katalog Barang</x-slot>

    @push('styles')
    <style>
        .range-slider-thumb::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            pointer-events: auto;
            width: 18px;
            height: 18px;
            border-radius: 9999px;
            background-color: #0f172a;
            border: 2.5px solid #ffffff;
            box-shadow: 0 2px 6px -1px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(15, 23, 42, 0.15);
            cursor: grab;
            transition: transform 0.15s ease, background-color 0.15s ease, box-shadow 0.15s ease;
        }
        .range-slider-thumb::-webkit-slider-thumb:hover {
            transform: scale(1.18);
            background-color: #000000;
            box-shadow: 0 4px 10px -1px rgba(0, 0, 0, 0.5), 0 0 0 2px rgba(15, 23, 42, 0.25);
        }
        .range-slider-thumb::-webkit-slider-thumb:active {
            cursor: grabbing;
            transform: scale(1.28);
        }
        .range-slider-thumb::-moz-range-thumb {
            pointer-events: auto;
            width: 18px;
            height: 18px;
            border-radius: 9999px;
            background-color: #0f172a;
            border: 2.5px solid #ffffff;
            box-shadow: 0 2px 6px -1px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(15, 23, 42, 0.15);
            cursor: grab;
            transition: transform 0.15s ease, background-color 0.15s ease, box-shadow 0.15s ease;
        }
        .range-slider-thumb::-moz-range-thumb:hover {
            transform: scale(1.18);
            background-color: #000000;
            box-shadow: 0 4px 10px -1px rgba(0, 0, 0, 0.5), 0 0 0 2px rgba(15, 23, 42, 0.25);
        }
        .range-slider-thumb::-moz-range-thumb:active {
            cursor: grabbing;
            transform: scale(1.28);
        }
    </style>
    @endpush

    <div class="relative min-h-[calc(100vh-140px)] py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 font-normal">
                <a href="{{ route('home') }}" class="hover:text-slate-900 transition">Beranda</a>
                <span class="text-slate-400">/</span>
                <a href="{{ route('categories') }}" class="hover:text-slate-900 transition">Kategori</a>
                <span class="text-slate-400">/</span>
                <span class="font-bold text-slate-900">{{ $currentCategory->name ?? 'Semua Produk' }}</span>
            </nav>

            {{-- Category Header Banner --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div class="flex items-center gap-4">
                    {{-- Category Icon Box --}}
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 shadow-xs"
                         style="{{ $currentCategory?->icon_meta['bg'] ?? 'background-color: #f1f5f9; color: #0f172a;' }}">
                        {!! $currentCategory?->icon_meta['svg'] ?? '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>' !!}
                    </div>

                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                            {{ $currentCategory->name ?? 'Semua Produk' }}
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-2xl leading-relaxed">
                            {{ $currentCategory->description ?? 'Temukan seluruh barang preloved berkualitas dari sesama mahasiswa FIKOM.' }}
                        </p>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-2.5 shrink-0">
                    <a href="{{ route('home') }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-slate-300 rounded-lg text-xs sm:text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition shadow-xs">
                        <span>&larr;</span>
                        <span>Kembali ke Beranda</span>
                    </a>
                    <a href="{{ route('categories') }}"
                       class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg text-xs sm:text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition shadow-xs">
                        Semua Kategori
                    </a>
                </div>
            </div>

            {{-- Main Layout: 2 Columns (Filter Sidebar + Products Area) --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                {{-- Left Sidebar (Filter Card) --}}
                <aside class="lg:col-span-3">
                    <form id="filter-form" method="GET" action="{{ route('catalog') }}"
                          class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs">

                        {{-- Preserve category, search, and sort in form --}}
                        <input type="hidden" name="category" value="{{ $selectedCategorySlug }}">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <input type="hidden" name="sort" id="form-hidden-sort" value="{{ request('sort', 'latest') }}">

                        {{-- Filter Card Header --}}
                        <div class="flex items-center justify-between mb-5">
                            <span class="font-bold text-slate-900 text-sm sm:text-base">Filter</span>
                            <a href="{{ route('catalog', ['category' => $selectedCategorySlug]) }}"
                               class="text-xs text-slate-400 hover:text-slate-700 transition">
                                Reset
                            </a>
                        </div>

                        {{-- Section HARGA --}}
                        <div class="mb-6">
                            <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-2.5">
                                HARGA
                            </label>

                            @php
                                $hasMin = request()->filled('min_price');
                                $hasMax = request()->filled('max_price');
                                $catalogCeiling = $maxCatalogPrice ?? 20000000;
                                $rawMin = $hasMin ? (int) preg_replace('/[^0-9]/', '', request('min_price')) : 0;
                                $rawMax = $hasMax ? (int) preg_replace('/[^0-9]/', '', request('max_price')) : $catalogCeiling;
                            @endphp

                            <div class="grid grid-cols-2 gap-2 mb-4">
                                <div>
                                    <input type="text" id="min_price_display"
                                           value="Rp {{ number_format($rawMin, 0, ',', '.') }}"
                                           class="w-full text-xs text-slate-800 font-medium px-2.5 py-2 rounded-lg border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:border-slate-400 transition">
                                    <input type="hidden" name="min_price" id="min_price_input" value="{{ $hasMin ? $rawMin : '' }}">
                                </div>
                                <div>
                                    <input type="text" id="max_price_display"
                                           value="Rp {{ number_format($rawMax, 0, ',', '.') }}"
                                           class="w-full text-xs text-slate-800 font-medium px-2.5 py-2 rounded-lg border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:border-slate-400 transition">
                                    <input type="hidden" name="max_price" id="max_price_input" value="{{ $hasMax ? $rawMax : '' }}">
                                </div>
                            </div>

                            {{-- Dual Thumb Range Slider --}}
                            <div class="relative py-3 px-1">
                                <div class="relative h-1.5 w-full bg-slate-200 rounded-full">
                                    <div id="slider-range-bar" class="absolute h-full bg-slate-900 rounded-full" style="left: 0%; right: 0%;"></div>
                                </div>
                                <input type="range" id="min_range" min="0" max="{{ $catalogCeiling }}" step="100000"
                                       value="{{ $rawMin }}"
                                       class="range-slider-thumb absolute w-full top-2.5 left-0 appearance-none bg-transparent pointer-events-none cursor-pointer">
                                <input type="range" id="max_range" min="0" max="{{ $catalogCeiling }}" step="100000"
                                       value="{{ $rawMax }}"
                                       class="range-slider-thumb absolute w-full top-2.5 left-0 appearance-none bg-transparent pointer-events-none cursor-pointer">
                            </div>
                        </div>

                        {{-- Section KONDISI --}}
                        <div class="mb-6">
                            <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-2.5">
                                KONDISI
                            </label>

                            @php $activeCondition = request('condition', ''); @endphp
                            <div class="space-y-2.5">
                                <label class="flex items-center gap-2.5 cursor-pointer text-xs text-slate-700 hover:text-slate-900 select-none">
                                    <input type="radio" name="condition" value=""
                                           {{ empty($activeCondition) ? 'checked' : '' }}
                                           class="w-4 h-4 text-black focus:ring-black border-slate-300">
                                    <span>Semua kondisi</span>
                                </label>
                                <label class="flex items-center gap-2.5 cursor-pointer text-xs text-slate-700 hover:text-slate-900 select-none">
                                    <input type="radio" name="condition" value="Seperti baru"
                                           {{ $activeCondition === 'Seperti baru' ? 'checked' : '' }}
                                           class="w-4 h-4 text-black focus:ring-black border-slate-300">
                                    <span>Seperti baru</span>
                                </label>
                                <label class="flex items-center gap-2.5 cursor-pointer text-xs text-slate-700 hover:text-slate-900 select-none">
                                    <input type="radio" name="condition" value="Baik"
                                           {{ $activeCondition === 'Baik' ? 'checked' : '' }}
                                           class="w-4 h-4 text-black focus:ring-black border-slate-300">
                                    <span>Baik</span>
                                </label>
                                <label class="flex items-center gap-2.5 cursor-pointer text-xs text-slate-700 hover:text-slate-900 select-none">
                                    <input type="radio" name="condition" value="Layak pakai"
                                           {{ $activeCondition === 'Layak pakai' ? 'checked' : '' }}
                                           class="w-4 h-4 text-black focus:ring-black border-slate-300">
                                    <span>Layak pakai</span>
                                </label>
                            </div>
                        </div>

                        {{-- Terapkan Filter Button --}}
                        <button type="submit"
                                class="w-full bg-black hover:bg-neutral-800 text-white font-medium text-xs sm:text-sm py-2.5 rounded-lg transition shadow-xs text-center">
                            Terapkan Filter
                        </button>
                    </form>
                </aside>

                {{-- Right Main Content Area --}}
                <main class="lg:col-span-9">

                    {{-- Top Status Bar: Count summary & Sort dropdown --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                        <p class="text-xs sm:text-sm text-slate-600">
                            @if($products->total() > 0)
                                Menampilkan {{ $products->firstItem() }}-{{ $products->lastItem() }} dari {{ $products->total() }} barang
                            @else
                                Menampilkan 0 barang
                            @endif
                        </p>

                        <div class="flex items-center self-end sm:self-auto">
                            <select id="sort-dropdown"
                                    class="bg-white border border-slate-300 rounded-lg px-3 py-1.5 text-xs sm:text-sm text-slate-700 focus:outline-none focus:ring-1 focus:ring-slate-400">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Urutkan: Terbaru</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Urutkan: Harga Terendah</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Urutkan: Harga Tertinggi</option>
                            </select>
                        </div>
                    </div>

                    {{-- Products Grid --}}
                    @if($products->isEmpty())
                        <div class="bg-white rounded-2xl border border-slate-200/90 p-12 text-center shadow-xs">
                            <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                <x-heroicon-o-magnifying-glass class="w-8 h-8"/>
                            </div>
                            <h3 class="text-base font-bold text-slate-800">Belum ada barang di kategori ini</h3>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-sm mx-auto">
                                Belum ada barang yang sesuai dengan filter pencarian ini. Anda dapat mengunggah barang Anda sendiri melalui tombol Mulai Jual.
                            </p>
                            <div class="mt-4 flex items-center justify-center gap-3">
                                <a href="{{ route('catalog', ['category' => $selectedCategorySlug]) }}"
                                   class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-semibold shadow-xs transition">
                                    Reset Filter
                                </a>
                                <a href="{{ route('products.create') }}"
                                   class="inline-flex items-center px-4 py-2 bg-black hover:bg-neutral-800 text-white rounded-lg text-xs font-semibold shadow-xs transition">
                                    Mulai Jual
                                </a>
                            </div>
                        </div>
                    @else
                        {{-- 3-Column Product Grid --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            @foreach($products as $product)
                                <x-product-card :product="$product"/>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-10 flex justify-center">
                            {{ $products->links('components.pagination') }}
                        </div>
                    @endif
                </main>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const minRange = document.getElementById('min_range');
            const maxRange = document.getElementById('max_range');
            const minInput = document.getElementById('min_price_input');
            const maxInput = document.getElementById('max_price_input');
            const minDisplay = document.getElementById('min_price_display');
            const maxDisplay = document.getElementById('max_price_display');
            const sliderBar = document.getElementById('slider-range-bar');
            const filterForm = document.getElementById('filter-form');
            const sortDropdown = document.getElementById('sort-dropdown');
            const hiddenSort = document.getElementById('form-hidden-sort');

            const maxSliderLimit = {{ $catalogCeiling }};

            function formatRupiah(num) {
                return 'Rp ' + Number(num).toLocaleString('id-ID');
            }

            function parseRupiah(str) {
                return parseInt(str.replace(/[^0-9]/g, '')) || 0;
            }

            function updateSliderBar() {
                const minVal = parseInt(minRange.value) || 0;
                const maxVal = parseInt(maxRange.value) || maxSliderLimit;
                const leftPercent = Math.max(0, Math.min(100, (minVal / maxSliderLimit) * 100));
                const rightPercent = Math.max(0, Math.min(100, 100 - ((maxVal / maxSliderLimit) * 100)));

                if (sliderBar) {
                    sliderBar.style.left = leftPercent + '%';
                    sliderBar.style.right = rightPercent + '%';
                }
            }

            if (minRange && maxRange) {
                minRange.addEventListener('input', function () {
                    let minVal = parseInt(minRange.value);
                    let maxVal = parseInt(maxRange.value);

                    if (minVal > maxVal) {
                        minVal = maxVal;
                        minRange.value = minVal;
                    }
                    minInput.value = minVal;
                    minDisplay.value = formatRupiah(minVal);
                    updateSliderBar();
                });

                maxRange.addEventListener('input', function () {
                    let minVal = parseInt(minRange.value);
                    let maxVal = parseInt(maxRange.value);

                    if (maxVal < minVal) {
                        maxVal = minVal;
                        maxRange.value = maxVal;
                    }
                    maxInput.value = maxVal;
                    maxDisplay.value = formatRupiah(maxVal);
                    updateSliderBar();
                });

                minDisplay.addEventListener('change', function () {
                    let val = parseRupiah(minDisplay.value);
                    let maxVal = parseInt(maxRange.value);
                    if (val > maxVal) val = maxVal;
                    minRange.value = val;
                    minInput.value = val;
                    minDisplay.value = formatRupiah(val);
                    updateSliderBar();
                });

                maxDisplay.addEventListener('change', function () {
                    let val = parseRupiah(maxDisplay.value);
                    let minVal = parseInt(minRange.value);
                    if (val < minVal) val = minVal;
                    if (val > maxSliderLimit) val = maxSliderLimit;
                    maxRange.value = val;
                    maxInput.value = val;
                    maxDisplay.value = formatRupiah(val);
                    updateSliderBar();
                });

                updateSliderBar();
            }

            // Exclude default min / max prices on submit if untouched
            if (filterForm) {
                filterForm.addEventListener('submit', function () {
                    const minVal = parseInt(minInput.value) || 0;
                    const maxVal = parseInt(maxInput.value) || maxSliderLimit;
                    if (minVal <= 0) {
                        minInput.disabled = true;
                    }
                    if (maxVal >= maxSliderLimit) {
                        maxInput.disabled = true;
                    }
                });
            }

            // Sync sort dropdown directly with URL without polluting price bounds
            if (sortDropdown) {
                sortDropdown.addEventListener('change', function () {
                    const url = new URL(window.location.href);
                    url.searchParams.set('sort', this.value);
                    url.searchParams.set('page', '1');
                    window.location.href = url.toString();
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
