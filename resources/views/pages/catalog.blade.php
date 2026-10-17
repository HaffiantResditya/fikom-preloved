<x-app-layout>
    <x-slot name="title">Katalog Barang</x-slot>

    <div class="relative min-h-[calc(100vh-140px)] py-8 sm:py-10">
        {{-- Soft pastel ambient blur effects --}}
        <div class="absolute top-0 left-0 w-80 h-80 bg-blue-100/40 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-20 right-0 w-80 h-80 bg-rose-100/40 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Breadcrumb --}}
            <nav class="text-xs text-gray-500 mb-4 flex items-center gap-1.5">
                <a href="{{ route('home') }}" class="hover:text-gray-800 transition">Beranda</a>
                <span>/</span>
                <span class="font-semibold text-gray-800">Katalog</span>
            </nav>

            {{-- Header Box --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-7 shadow-sm mb-6">
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-1 h-3.5 bg-gray-400 rounded-full inline-block"></span>
                    <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Katalog Barang Preloved</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Katalog Barang</h1>
                <p class="text-gray-500 text-xs sm:text-sm mt-1">Temukan barang bekas berkualitas dari sesama mahasiswa FIKOM</p>

                {{-- Filter Bar --}}
                <form method="GET" action="{{ route('catalog') }}" class="mt-6 flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari barang kos: kasur, meja, galon..." id="search-input"
                               class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2.5 text-xs sm:text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-gray-400 transition">
                    </div>

                    <select name="category" id="category-filter" class="border border-gray-300 rounded-lg px-4 py-2.5 text-xs sm:text-sm text-gray-700 bg-white focus:outline-none focus:ring-1 focus:ring-gray-400">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="condition" id="condition-filter" class="border border-gray-300 rounded-lg px-4 py-2.5 text-xs sm:text-sm text-gray-700 bg-white focus:outline-none focus:ring-1 focus:ring-gray-400">
                        <option value="">Semua Kondisi</option>
                        <option value="Baru" {{ request('condition') === 'Baru' ? 'selected' : '' }}>Baru</option>
                        <option value="Bekas Seperti Baru" {{ request('condition') === 'Bekas Seperti Baru' ? 'selected' : '' }}>Bekas Seperti Baru</option>
                        <option value="Bekas Layak Pakai" {{ request('condition') === 'Bekas Layak Pakai' ? 'selected' : '' }}>Bekas Layak Pakai</option>
                    </select>

                    <button type="submit" id="search-submit"
                            class="bg-neutral-900 hover:bg-neutral-800 text-white font-medium px-6 py-2.5 rounded-lg transition text-xs sm:text-sm shadow-sm whitespace-nowrap">
                        Cari
                    </button>
                    @if(request()->hasAny(['search', 'category', 'condition']))
                        <a href="{{ route('catalog') }}" id="clear-filter"
                           class="text-xs sm:text-sm text-gray-500 hover:text-gray-700 flex items-center justify-center px-3 py-2 border border-gray-300 rounded-lg bg-white">Reset</a>
                    @endif
                </form>
            </div>

            {{-- Hasil --}}
            @if($products->isEmpty())
                <div class="text-center py-24 bg-white rounded-2xl border border-gray-200 text-gray-400">
                    <x-heroicon-o-magnifying-glass class="w-14 h-14 mx-auto mb-3 opacity-30"/>
                    <p class="text-base font-semibold text-gray-700">Tidak ada barang yang ditemukan.</p>
                    <p class="text-xs text-gray-400 mt-1">Coba kata kunci lain atau reset filter pencarian Anda.</p>
                    <a href="{{ route('catalog') }}" class="text-neutral-900 font-semibold text-xs mt-3 inline-block hover:underline">Reset filter</a>
                </div>
            @else
                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs font-medium text-gray-500">Menampilkan {{ $products->total() }} barang tersedia</p>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3.5 sm:gap-4">
                    @foreach($products as $product)
                        <x-product-card :product="$product"/>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-10">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
