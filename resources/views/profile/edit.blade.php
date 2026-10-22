<x-app-layout>
    <x-slot name="title">Edit Profile Saya</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10"
         x-data="{
             showCancelModal: false,
             showPasswordModal: false,
             showDeleteModal: false,
             showPasswordInline: false,
             photoPreview: null,
             removePhoto: false
         }">

        {{-- Header: Breadcrumb & Title & Mode Sunting Aktif Badge --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 sm:mb-8">
            <div>
                <nav class="flex items-center gap-1.5 text-xs text-slate-400 font-medium mb-1.5">
                    <a href="{{ route('home') }}" class="hover:text-slate-700 transition">Beranda</a>
                    <span>&gt;</span>
                    <a href="{{ route('profile.show') }}" class="hover:text-slate-700 transition">Profile</a>
                    <span>&gt;</span>
                    <span class="text-slate-800 font-semibold">Edit Profile</span>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Edit Profile Saya</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Perbarui informasi profil dan kontak akun FIKOM PRELOVED Anda.
                </p>
            </div>

            {{-- Mode Sunting Aktif Indicator --}}
            <div class="flex-shrink-0">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-full text-xs font-semibold shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    Mode Sunting Aktif
                </span>
            </div>
        </div>

        {{-- Main Form Wrapping Both Columns (Allows submitting photo & inputs together) --}}
        <form id="edit-profile-form"
              method="POST"
              action="{{ route('profile.update') }}"
              enctype="multipart/form-data">
            @csrf
            @method('patch')

            {{-- Hidden Remove Photo Flag --}}
            <input type="hidden" name="remove_photo" :value="removePhoto ? 1 : 0">

            {{-- Main Two-Column Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">

                {{-- ═══════════════════════════════════════════════════════════
                     KOLOM KIRI (PROFILE & RIWAYAT TRANSAKSI)
                     ═══════════════════════════════════════════════════════════ --}}
                <div class="lg:col-span-4 space-y-6">

                    {{-- CARD 1: PROFILE (EDITING STATE) --}}
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm relative overflow-hidden">
                        {{-- Badges Header Row --}}
                        <div class="flex items-center justify-between mb-5">
                            <span class="inline-block px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[11px] font-bold tracking-wider uppercase">
                                PROFILE
                            </span>
                            <span class="inline-block px-2.5 py-0.5 bg-blue-50 text-blue-600 border border-blue-200 rounded-full text-[10px] font-bold">
                                Editing
                            </span>
                        </div>

                        {{-- Profile Detail Row --}}
                        <div class="flex items-center gap-4 mb-5">
                            <div class="w-16 h-16 sm:w-18 sm:h-18 rounded-full bg-slate-100 border-2 border-slate-200 flex items-center justify-center text-slate-400 overflow-hidden flex-shrink-0 shadow-inner relative">
                                <template x-if="photoPreview">
                                    <img :src="photoPreview" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!photoPreview">
                                    <span>
                                        @if($user->profile_photo)
                                            <img src="{{ Storage::url($user->profile_photo) }}"
                                                 x-show="!removePhoto"
                                                 alt="{{ $user->name }}"
                                                 class="w-full h-full object-cover">
                                            <svg x-show="removePhoto" class="w-10 h-10 text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                            </svg>
                                        @else
                                            <svg class="w-10 h-10 text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                            </svg>
                                        @endif
                                    </span>
                                </template>
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

                        {{-- Upload Foto Baru Section (Seperti Wireframe) --}}
                        <div class="pt-3 border-t border-slate-100">
                            <input type="file"
                                   id="profile_photo"
                                   name="profile_photo"
                                   accept="image/png,image/jpeg,image/jpg"
                                   class="hidden"
                                   @change="
                                       const file = $event.target.files[0];
                                       if (file) {
                                           removePhoto = false;
                                           const reader = new FileReader();
                                           reader.onload = (e) => { photoPreview = e.target.result; };
                                           reader.readAsDataURL(file);
                                       }
                                   ">

                            <div class="flex items-center gap-2">
                                <button type="button"
                                        @click="document.getElementById('profile_photo').click()"
                                        class="flex-1 flex items-center justify-center gap-2 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 text-xs sm:text-sm font-semibold py-2.5 px-3 rounded-xl transition shadow-xs">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Upload Foto Baru</span>
                                </button>

                                {{-- Trash button to remove photo --}}
                                <button type="button"
                                        @click="photoPreview = null; removePhoto = true; document.getElementById('profile_photo').value = '';"
                                        title="Hapus foto profil"
                                        class="p-2.5 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-600 rounded-xl transition shadow-xs">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>

                            <p class="text-[11px] text-slate-400 mt-2 text-center">
                                Maksimal 2MB, format JPG atau PNG
                            </p>
                            @error('profile_photo')
                                <p class="text-red-500 text-xs mt-1 text-center">{{ $message }}</p>
                            @enderror
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
                     KOLOM KANAN (FORM INFORMASI AKUN EDITABLE)
                     ═══════════════════════════════════════════════════════════ --}}
                <div class="lg:col-span-8 space-y-6">

                    {{-- CARD INFORMASI AKUN --}}
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm">
                        {{-- Header Row --}}
                        <div class="flex items-center justify-between mb-5">
                            <span class="inline-block px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[11px] font-bold tracking-wider uppercase">
                                INFORMASI AKUN
                            </span>
                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <span class="text-xs text-amber-600 font-medium bg-amber-50 px-2.5 py-1 rounded-full">
                                    Email Belum Terverifikasi
                                </span>
                            @endif
                        </div>

                        {{-- Editable Fields --}}
                        <div class="space-y-4 sm:space-y-5">
                            {{-- Row 1: Nama Lengkap & Email Kampus --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Nama Lengkap --}}
                                <div>
                                    <div class="flex items-center justify-between mb-1.5">
                                        <label for="name" class="block text-xs font-semibold text-slate-600">
                                            Nama Lengkap
                                        </label>
                                        <span class="text-[11px] text-slate-400">Dapat diedit</span>
                                    </div>
                                    <div class="relative">
                                        <input type="text"
                                               id="name"
                                               name="name"
                                               value="{{ old('name', $user->name) }}"
                                               required
                                               class="w-full bg-white border border-slate-200 rounded-xl pl-4 pr-10 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 transition @error('name') border-red-400 @enderror">
                                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    @error('name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Email Kampus --}}
                                <div>
                                    <div class="flex items-center justify-between mb-1.5">
                                        <label for="email" class="block text-xs font-semibold text-slate-600">
                                            Email Kampus
                                        </label>
                                        <span class="text-[11px] text-slate-400">Dapat diedit</span>
                                    </div>
                                    <div class="relative">
                                        <input type="email"
                                               id="email"
                                               name="email"
                                               value="{{ old('email', $user->email) }}"
                                               required
                                               class="w-full bg-white border border-slate-200 rounded-xl pl-4 pr-10 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 transition @error('email') border-red-400 @enderror">
                                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Row 2: Nomor Telepon & Lokasi --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Nomor Telepon --}}
                                <div>
                                    <div class="flex items-center justify-between mb-1.5">
                                        <label for="whatsapp_number" class="block text-xs font-semibold text-slate-600">
                                            Nomor Telepon
                                        </label>
                                        <span class="text-[11px] text-slate-400">Dapat diedit</span>
                                    </div>
                                    <div class="relative">
                                        <input type="text"
                                               id="whatsapp_number"
                                               name="whatsapp_number"
                                               value="{{ old('whatsapp_number', $user->whatsapp_number) }}"
                                               placeholder="081234567890"
                                               class="w-full bg-white border border-slate-200 rounded-xl pl-4 pr-10 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 transition @error('whatsapp_number') border-red-400 @enderror">
                                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    @error('whatsapp_number')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Lokasi --}}
                                <div>
                                    <div class="flex items-center justify-between mb-1.5">
                                        <label for="location" class="block text-xs font-semibold text-slate-600">
                                            Lokasi
                                        </label>
                                        <span class="text-[11px] text-slate-400">Dapat diedit</span>
                                    </div>
                                    <div class="relative">
                                        <input type="text"
                                               id="location"
                                               name="location"
                                               value="{{ old('location', $user->location ?: 'Dorm FIKOM') }}"
                                               placeholder="Dorm FIKOM / Kos Palur"
                                               class="w-full bg-white border border-slate-200 rounded-xl pl-4 pr-10 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 transition @error('location') border-red-400 @enderror">
                                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    @error('location')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Row 2b: Jurusan / Program Studi (Mempertahankan Struktur Profil Sebelumnya) --}}
                            <div>
                                <label for="major" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                    Program Studi / Jurusan
                                </label>
                                <select id="major"
                                        name="major"
                                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 transition @error('major') border-red-400 @enderror">
                                    <option value="">-- Pilih Jurusan Mahasiswa --</option>
                                    @foreach(\App\Models\User::MAJORS as $major)
                                        <option value="{{ $major }}" {{ old('major', $user->major) === $major ? 'selected' : '' }}>
                                            {{ $major }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('major')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Row 3: Bio --}}
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label for="bio" class="block text-xs font-semibold text-slate-600">
                                        Bio
                                    </label>
                                    <span class="text-[11px] text-slate-400">Dapat diedit</span>
                                </div>
                                <div class="relative">
                                    <textarea id="bio"
                                              name="bio"
                                              rows="2"
                                              placeholder="Mahasiswa FIKOM yang suka jual beli barang kos bekas berkualitas."
                                              class="w-full bg-white border border-slate-200 rounded-xl pl-4 pr-10 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 transition resize-none @error('bio') border-red-400 @enderror">{{ old('bio', $user->bio ?: 'Mahasiswa FIKOM yang suka jual beli barang kos bekas berkualitas.') }}</textarea>
                                    <div class="absolute top-3 right-3 text-slate-400 pointer-events-none">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </div>
                                </div>
                                @error('bio')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Row 4: Kata Sandi --}}
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label class="block text-xs font-semibold text-slate-600">
                                        Kata Sandi
                                    </label>
                                    <span class="text-[11px] text-slate-400">Dapat diubah</span>
                                </div>
                                <div class="relative">
                                    <input :type="showPasswordInline ? 'text' : 'password'"
                                           readonly
                                           value="supersecretpassword"
                                           class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-4 pr-10 py-2.5 text-xs sm:text-sm text-slate-500 tracking-wider cursor-pointer focus:outline-none"
                                           @click="showPasswordModal = true">
                                    <button type="button"
                                            @click="showPasswordInline = !showPasswordInline"
                                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                                        <svg x-show="!showPasswordInline" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <svg x-show="showPasswordInline" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons: Batal & Simpan Perubahan (Sesuai Wireframe) --}}
                        <div class="mt-8 pt-5 border-t border-slate-100 flex items-center justify-between gap-4">
                            <div>
                                <button type="button"
                                        @click="showDeleteModal = true"
                                        class="text-xs text-red-500 hover:text-red-700 font-medium transition hover:underline">
                                    Hapus Akun Saya
                                </button>
                            </div>

                            <div class="flex items-center gap-3">
                                {{-- Tombol Batal -> Memicu Modal Konfirmasi Pembatalan --}}
                                <button type="button"
                                        @click="showCancelModal = true"
                                        class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs sm:text-sm font-semibold py-2.5 px-6 rounded-xl transition shadow-xs">
                                    Batal
                                </button>

                                {{-- Tombol Simpan Perubahan --}}
                                <button type="submit"
                                        class="bg-slate-900 hover:bg-black text-white text-xs sm:text-sm font-semibold py-2.5 px-6 rounded-xl transition shadow-sm">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </form>

        {{-- ═══════════════════════════════════════════════════════════════
             MODAL 1: KONFIRMASI BATALKAN PERUBAHAN PROFIL
             (Persis seperti Wireframe Gambar 2)
             ═══════════════════════════════════════════════════════════════ --}}
        <div x-show="showCancelModal"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             style="background-color: rgba(15, 23, 42, 0.45); backdrop-filter: blur(4px);">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-3xl max-w-sm sm:max-w-md w-full p-6 sm:p-8 text-center shadow-2xl border border-slate-100 relative"
                     @click.outside="showCancelModal = false">

                    {{-- Close X Button --}}
                    <button type="button"
                            @click="showCancelModal = false"
                            class="absolute top-5 right-5 text-slate-400 hover:text-slate-600">
                        <x-heroicon-o-x-mark class="w-5 h-5"/>
                    </button>

                    {{-- Warning Icon in Circular Container --}}
                    <div class="w-14 h-14 rounded-full bg-amber-50 text-amber-500 mx-auto flex items-center justify-center mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>

                    {{-- Modal Title --}}
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-2">
                        Batalkan Perubahan Profil?
                    </h3>

                    {{-- Modal Description --}}
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed mb-6">
                        Perubahan yang belum disimpan akan dibatalkan dan informasi akun akan kembali ke data sebelumnya. Apakah Anda yakin ingin membatalkan?
                    </p>

                    {{-- Modal Action Buttons --}}
                    <div class="flex items-center justify-center gap-3">
                        <button type="button"
                                @click="showCancelModal = false"
                                class="flex-1 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs sm:text-sm font-semibold py-2.5 px-4 rounded-xl transition shadow-xs">
                            Lanjut Edit
                        </button>

                        <a href="{{ route('profile.show') }}"
                           class="flex-1 bg-rose-600 hover:bg-rose-700 text-white text-xs sm:text-sm font-semibold py-2.5 px-4 rounded-xl transition text-center shadow-xs">
                            Ya, Batalkan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════
             MODAL 2: UBAH KATA SANDI (Mempertahankan Struktur Sebelumnya)
             ═══════════════════════════════════════════════════════════════ --}}
        <div x-show="showPasswordModal"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             style="background-color: rgba(15, 23, 42, 0.45); backdrop-filter: blur(4px);">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-slate-100"
                     @click.outside="showPasswordModal = false">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-slate-900">Perbarui Kata Sandi</h3>
                        <button @click="showPasswordModal = false" class="text-slate-400 hover:text-slate-600">
                            <x-heroicon-o-x-mark class="w-5 h-5"/>
                        </button>
                    </div>
                    <p class="text-xs text-slate-500 mb-6">
                        Pastikan kata sandi baru Anda minimal 8 karakter dengan kombinasi yang kuat.
                    </p>

                    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                        @csrf
                        @method('put')

                        <div>
                            <label for="current_password" class="block text-xs font-semibold text-slate-600 mb-1">Kata Sandi Saat Ini</label>
                            <input type="password" id="current_password" name="current_password" required
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-400 transition">
                            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
                        </div>

                        <div>
                            <label for="update_password" class="block text-xs font-semibold text-slate-600 mb-1">Kata Sandi Baru</label>
                            <input type="password" id="update_password" name="password" required
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-400 transition">
                            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-semibold text-slate-600 mb-1">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-400 transition">
                            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-3">
                            <button type="button" @click="showPasswordModal = false"
                                    class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-semibold py-2.5 px-4 rounded-xl transition">
                                Batal
                            </button>
                            <button type="submit"
                                    class="bg-slate-900 hover:bg-black text-white text-xs sm:text-sm font-semibold py-2.5 px-5 rounded-xl transition">
                                Simpan Sandi Baru
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════
             MODAL 3: HAPUS AKUN (Mempertahankan Struktur Sebelumnya)
             ═══════════════════════════════════════════════════════════════ --}}
        <div x-show="showDeleteModal"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             style="background-color: rgba(15, 23, 42, 0.45); backdrop-filter: blur(4px);">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-slate-100"
                     @click.outside="showDeleteModal = false">
                    <h3 class="text-lg font-bold text-red-600 mb-2">Hapus Akun Permanen?</h3>
                    <p class="text-xs text-slate-600 mb-5 leading-relaxed">
                        Setelah akun dihapus, seluruh data profil dan riwayat iklan Anda akan dihapus secara permanen. Masukkan kata sandi Anda untuk mengonfirmasi.
                    </p>

                    <form method="POST" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('delete')

                        <div class="mb-5">
                            <label for="delete_password" class="block text-xs font-semibold text-slate-600 mb-1">Kata Sandi</label>
                            <input type="password" id="delete_password" name="password" required placeholder="Kata sandi akun Anda"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-400 transition">
                            <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1" />
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <button type="button" @click="showDeleteModal = false"
                                    class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-semibold py-2.5 px-4 rounded-xl transition">
                                Batal
                            </button>
                            <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white text-xs sm:text-sm font-semibold py-2.5 px-5 rounded-xl transition">
                                Hapus Akun
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
