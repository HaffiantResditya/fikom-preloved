<x-app-layout>
    <x-slot name="title">Profile Saya</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">

        {{-- Breadcrumb & Title --}}
        <div class="mb-6 sm:mb-8">
            <nav class="flex items-center gap-1.5 text-xs text-slate-400 font-medium mb-1.5">
                <a href="{{ route('home') }}" class="hover:text-slate-700 transition">Beranda</a>
                <span>&gt;</span>
                <span class="text-slate-800 font-semibold">Profile</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Profile Saya</h1>
        </div>

        {{-- Flash Messages --}}
        @if (session('status') === 'profile-updated')
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl px-4 py-3 flex items-center gap-2 text-sm shadow-sm">
                <x-heroicon-o-check-circle class="w-5 h-5 text-emerald-500 flex-shrink-0"/>
                <span>Data profil berhasil diperbarui.</span>
            </div>
        @endif
        @if (session('status') === 'password-updated')
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl px-4 py-3 flex items-center gap-2 text-sm shadow-sm">
                <x-heroicon-o-check-circle class="w-5 h-5 text-emerald-500 flex-shrink-0"/>
                <span>Kata sandi berhasil diperbarui.</span>
            </div>
        @endif

        {{-- Main Two-Column Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">

            {{-- ═══════════════════════════════════════════════════════════
                 KOLOM KIRI (PROFILE & RIWAYAT TRANSAKSI)
                 ═══════════════════════════════════════════════════════════ --}}
            <div class="lg:col-span-4 space-y-6">

                {{-- CARD 1: PROFILE --}}
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm relative overflow-hidden">
                    {{-- Badge --}}
                    <span class="inline-block px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[11px] font-bold tracking-wider uppercase mb-5">
                        PROFILE
                    </span>

                    {{-- Profile Detail Row --}}
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 sm:w-18 sm:h-18 rounded-full bg-slate-100 border-2 border-slate-200 flex items-center justify-center text-slate-400 overflow-hidden flex-shrink-0 shadow-inner">
                            @if($user->profile_photo)
                                <img src="{{ Storage::url($user->profile_photo) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-10 h-10 text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                </svg>
                            @endif
                        </div>

                        <div class="min-w-0 flex-1">
                            <h2 class="font-bold text-slate-900 text-base sm:text-lg leading-tight truncate" title="{{ $user->name }}">
                                {{ $user->name }}
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-400 truncate mt-0.5" title="{{ $user->email }}">
                                {{ $user->email }}
                            </p>
                            <div class="mt-1 space-y-0.5">
                                <p class="text-xs text-slate-600 font-medium truncate">
                                    {{ $user->location ?: 'Dorm FIKOM' }}
                                </p>
                                <p class="text-xs text-slate-500 font-medium truncate">
                                    Mahasiswa {{ $user->major ?: 'FIKOM' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center gap-3">
                        <a href="{{ route('profile.edit') }}"
                           class="flex-1 text-center bg-slate-900 hover:bg-black text-white text-xs sm:text-sm font-semibold py-2.5 px-4 rounded-xl transition duration-150 shadow-sm">
                            Edit Profile
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="flex-1">
                            @csrf
                            <button type="submit"
                                    class="w-full text-center bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs sm:text-sm font-semibold py-2.5 px-4 rounded-xl transition duration-150 shadow-sm">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>

                {{-- CARD 2: RIWAYAT TRANSAKSI --}}
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                    {{-- Badge --}}
                    <span class="inline-block px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[11px] font-bold tracking-wider uppercase mb-4">
                        RIWAYAT TRANSAKSI
                    </span>

                    {{-- Table Header --}}
                    <div class="grid grid-cols-12 text-[11px] font-bold text-slate-400 uppercase tracking-wider pb-2 border-b border-slate-100">
                        <div class="col-span-6">BARANG</div>
                        <div class="col-span-3 text-right">HARGA</div>
                        <div class="col-span-3 text-right">STATUS</div>
                    </div>

                    {{-- Table Body --}}
                    <div class="divide-y divide-slate-100">
                        @forelse($transactions as $trx)
                            <div class="grid grid-cols-12 items-center py-3 text-xs">
                                <div class="col-span-6 font-semibold text-slate-800 truncate pr-2" title="{{ $trx->title }}">
                                    {{ $trx->title }}
                                </div>
                                <div class="col-span-3 text-right font-medium text-slate-600">
                                    Rp {{ number_format($trx->price, 0, ',', '.') }}
                                </div>
                                <div class="col-span-3 text-right">
                                    @if($trx->status === 'pending')
                                        <span class="inline-block text-[10px] sm:text-[11px] font-semibold px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                            Menunggu
                                        </span>
                                    @elseif($trx->status === 'sold')
                                        <span class="inline-block text-[10px] sm:text-[11px] font-semibold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Selesai
                                        </span>
                                    @else
                                        <span class="inline-block text-[10px] sm:text-[11px] font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">
                                            {{ ucfirst($trx->status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            {{-- Fallback matching wireframe layout gracefully --}}
                            <div class="grid grid-cols-12 items-center py-3 text-xs">
                                <div class="col-span-6 font-semibold text-slate-800 truncate pr-2">
                                    Kasur Lipat Busa
                                </div>
                                <div class="col-span-3 text-right font-medium text-slate-600">
                                    Rp 150.000
                                </div>
                                <div class="col-span-3 text-right">
                                    <span class="inline-block text-[10px] sm:text-[11px] font-semibold px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                        Menunggu
                                    </span>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 items-center py-3 text-xs">
                                <div class="col-span-6 font-semibold text-slate-800 truncate pr-2">
                                    Meja Lipat Kayu
                                </div>
                                <div class="col-span-3 text-right font-medium text-slate-600">
                                    Rp 75.000
                                </div>
                                <div class="col-span-3 text-right">
                                    <span class="inline-block text-[10px] sm:text-[11px] font-semibold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Selesai
                                    </span>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            {{-- ═══════════════════════════════════════════════════════════
                 KOLOM KANAN (INFORMASI AKUN & BARANG SAYA)
                 ═══════════════════════════════════════════════════════════ --}}
            <div class="lg:col-span-8 space-y-6">

                {{-- CARD 1: INFORMASI AKUN (READ ONLY / VIEW MODE) --}}
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm" x-data="{ showPassword: false }">
                    {{-- Badge --}}
                    <div class="flex items-center justify-between mb-5">
                        <span class="inline-block px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[11px] font-bold tracking-wider uppercase">
                            INFORMASI AKUN
                        </span>
                        <a href="{{ route('profile.edit') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold transition flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            Ubah Data Akun
                        </a>
                    </div>

                    {{-- Data Akun Grid --}}
                    <div class="space-y-4 sm:space-y-5">
                        {{-- Row 1: Nama Lengkap & Email Kampus --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                    Nama Lengkap
                                </label>
                                <input type="text"
                                       readonly
                                       value="{{ $user->name }}"
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-slate-800 cursor-default focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                    Email Kampus
                                </label>
                                <input type="email"
                                       readonly
                                       value="{{ $user->email }}"
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-slate-800 cursor-default focus:outline-none">
                            </div>
                        </div>

                        {{-- Row 2: Nomor Telepon & Lokasi --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                    Nomor Telepon
                                </label>
                                <input type="text"
                                       readonly
                                       value="{{ $user->whatsapp_number ?: '081234567890' }}"
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-slate-800 cursor-default focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                    Lokasi
                                </label>
                                <input type="text"
                                       readonly
                                       value="{{ $user->location ?: 'Dorm FIKOM' }}"
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-slate-800 cursor-default focus:outline-none">
                            </div>
                        </div>

                        {{-- Row 2b: Program Studi / Jurusan (Struktur Sebelumnya) --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Program Studi / Jurusan
                            </label>
                            <input type="text"
                                   readonly
                                   value="{{ $user->major ?: 'Teknik Informatika' }}"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-slate-800 cursor-default focus:outline-none">
                        </div>

                        {{-- Row 3: Bio --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Bio
                            </label>
                            <input type="text"
                                   readonly
                                   value="{{ $user->bio ?: 'Mahasiswa FIKOM yang suka jual beli barang kos bekas berkualitas.' }}"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-slate-800 cursor-default focus:outline-none">
                        </div>

                        {{-- Row 4: Kata Sandi --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Kata Sandi
                            </label>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'"
                                       readonly
                                       value="supersecretpassword"
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-slate-500 tracking-wider cursor-default focus:outline-none">
                                <button type="button"
                                        @click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                                    <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD 2: BARANG SAYA --}}
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm" x-data="{ activeTab: 'all' }">
                    {{-- Header Row: Badge & Link Jual --}}
                    <div class="flex items-center justify-between mb-5">
                        <span class="inline-block px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[11px] font-bold tracking-wider uppercase">
                            BARANG SAYA
                        </span>
                        <a href="{{ route('products.create') }}"
                           class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-800 hover:text-black hover:underline transition">
                            <x-heroicon-o-plus class="w-3.5 h-3.5"/> Pasang Iklan
                        </a>
                    </div>

                    {{-- Filter Tabs Pills --}}
                    <div class="flex items-center gap-2 overflow-x-auto pb-2 mb-6 scrollbar-none text-xs">
                        {{-- Semua --}}
                        <button type="button"
                                @click="activeTab = 'all'"
                                :class="activeTab === 'all'
                                    ? 'bg-slate-900 text-white shadow-sm font-bold'
                                    : 'bg-slate-50 text-slate-600 hover:bg-slate-100 font-medium'"
                                class="px-3.5 py-1.5 rounded-full transition whitespace-nowrap flex items-center gap-1.5">
                            <span>Semua</span>
                            <span :class="activeTab === 'all' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'" class="px-1.5 py-0.2 rounded-full text-[10px]">
                                {{ $counts['all'] }}
                            </span>
                        </button>

                        {{-- Aktif --}}
                        <button type="button"
                                @click="activeTab = 'approved'"
                                :class="activeTab === 'approved'
                                    ? 'bg-slate-900 text-white shadow-sm font-bold'
                                    : 'bg-slate-50 text-slate-600 hover:bg-slate-100 font-medium'"
                                class="px-3.5 py-1.5 rounded-full transition whitespace-nowrap flex items-center gap-1.5">
                            <span>Aktif</span>
                            <span :class="activeTab === 'approved' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'" class="px-1.5 py-0.2 rounded-full text-[10px]">
                                {{ $counts['approved'] }}
                            </span>
                        </button>

                        {{-- Menunggu --}}
                        <button type="button"
                                @click="activeTab = 'pending'"
                                :class="activeTab === 'pending'
                                    ? 'bg-slate-900 text-white shadow-sm font-bold'
                                    : 'bg-slate-50 text-slate-600 hover:bg-slate-100 font-medium'"
                                class="px-3.5 py-1.5 rounded-full transition whitespace-nowrap flex items-center gap-1.5">
                            <span>Menunggu</span>
                            <span :class="activeTab === 'pending' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'" class="px-1.5 py-0.2 rounded-full text-[10px]">
                                {{ $counts['pending'] }}
                            </span>
                        </button>

                        {{-- Ditolak --}}
                        <button type="button"
                                @click="activeTab = 'rejected'"
                                :class="activeTab === 'rejected'
                                    ? 'bg-slate-900 text-white shadow-sm font-bold'
                                    : 'bg-slate-50 text-slate-600 hover:bg-slate-100 font-medium'"
                                class="px-3.5 py-1.5 rounded-full transition whitespace-nowrap flex items-center gap-1.5">
                            <span>Ditolak</span>
                            <span :class="activeTab === 'rejected' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'" class="px-1.5 py-0.2 rounded-full text-[10px]">
                                {{ $counts['rejected'] }}
                            </span>
                        </button>

                        {{-- Terjual --}}
                        <button type="button"
                                @click="activeTab = 'sold'"
                                :class="activeTab === 'sold'
                                    ? 'bg-slate-900 text-white shadow-sm font-bold'
                                    : 'bg-slate-50 text-slate-600 hover:bg-slate-100 font-medium'"
                                class="px-3.5 py-1.5 rounded-full transition whitespace-nowrap flex items-center gap-1.5">
                            <span>Terjual</span>
                            <span :class="activeTab === 'sold' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'" class="px-1.5 py-0.2 rounded-full text-[10px]">
                                {{ $counts['sold'] }}
                            </span>
                        </button>
                    </div>

                    {{-- Product Cards Grid --}}
                    @if($myProducts->isNotEmpty())
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                            @foreach($myProducts as $item)
                                @php
                                    $itemImage = $item->primaryImage?->image_path ?? $item->images?->first()?->image_path;
                                @endphp
                                <div x-show="activeTab === 'all' || activeTab === '{{ $item->status }}'"
                                     class="bg-white rounded-2xl border border-slate-100 overflow-hidden flex flex-col justify-between hover:shadow-md transition group p-2.5">

                                    {{-- Product Image --}}
                                    <div>
                                        <div class="w-full h-32 sm:h-36 rounded-xl overflow-hidden bg-slate-100 mb-2.5 relative">
                                            @if($itemImage)
                                                <img src="{{ Storage::url($itemImage) }}"
                                                     alt="{{ $item->title }}"
                                                     class="w-full h-full object-cover group-hover:scale-102 transition duration-300">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                    <x-heroicon-o-photo class="w-8 h-8"/>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Title & Price --}}
                                        <h4 class="font-bold text-slate-900 text-xs sm:text-sm truncate leading-snug" title="{{ $item->title }}">
                                            {{ $item->title }}
                                        </h4>
                                        <p class="text-xs font-semibold text-slate-500 mt-0.5">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </p>

                                        {{-- Reason if rejected (Seperti wireframe) --}}
                                        @if($item->status === 'rejected')
                                            <p class="text-[11px] text-red-500 font-medium leading-tight mt-1 truncate" title="Alasan: {{ $item->rejection_reason ?: 'Foto produk tidak lengkap' }}">
                                                Alasan: {{ $item->rejection_reason ?: 'Foto produk tidak lengkap' }}
                                            </p>
                                        @endif
                                    </div>

                                    {{-- Bottom Actions --}}
                                    <div class="pt-3 mt-2 border-t border-slate-100 flex items-center justify-between gap-1.5 flex-wrap">
                                        {{-- Status Badge --}}
                                        <div>
                                            @if($item->status === 'pending')
                                                <span class="inline-block text-[10px] font-bold px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 border border-amber-200">
                                                    Menunggu
                                                </span>
                                            @elseif($item->status === 'approved')
                                                <span class="inline-block text-[10px] font-bold px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    Aktif
                                                </span>
                                            @elseif($item->status === 'rejected')
                                                <span class="inline-block text-[10px] font-bold px-2 py-0.5 rounded-md bg-red-50 text-red-700 border border-red-200">
                                                    Ditolak
                                                </span>
                                            @elseif($item->status === 'sold')
                                                <span class="inline-block text-[10px] font-bold px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 border border-slate-200">
                                                    Terjual
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Action Buttons --}}
                                        <div class="flex items-center gap-1">
                                            @if($item->status === 'approved' || $item->status === 'sold')
                                                <a href="{{ route('products.show', $item->slug) }}"
                                                   class="px-2 py-1 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-lg text-[10px] sm:text-[11px] font-medium transition">
                                                    Lihat
                                                </a>
                                            @elseif($item->status === 'pending')
                                                <a href="{{ route('products.show', $item->slug) }}"
                                                   class="px-2 py-1 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-lg text-[10px] sm:text-[11px] font-medium transition">
                                                    Lihat
                                                </a>
                                            @endif

                                            @if($item->status === 'rejected')
                                                <a href="{{ route('products.create') }}"
                                                   class="px-2 py-1 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-lg text-[10px] sm:text-[11px] font-medium transition">
                                                    Perbaiki
                                                </a>
                                                <form method="POST" action="{{ route('products.destroy', $item) }}" onsubmit="return confirm('Hapus iklan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="px-2 py-1 bg-white border border-red-200 hover:bg-red-50 text-red-600 rounded-lg text-[10px] sm:text-[11px] font-medium transition">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        {{-- Empty State --}}
                        <div class="text-center py-12 text-slate-400">
                            <x-heroicon-o-shopping-bag class="w-12 h-12 mx-auto mb-3 opacity-30"/>
                            <p class="text-sm font-semibold text-slate-600">Belum ada barang yang diiklankan</p>
                            <p class="text-xs text-slate-400 mt-1 mb-4">Mulai jual perabot kos, buku modul, atau barang bekas Anda di sini.</p>
                            <a href="{{ route('products.create') }}"
                               class="inline-block bg-slate-900 hover:bg-black text-white text-xs font-semibold px-4 py-2 rounded-xl transition">
                                + Jual Barang Kos
                            </a>
                        </div>
                    @endif
                </div>

            </div>

        </div>

    </div>
</x-app-layout>
