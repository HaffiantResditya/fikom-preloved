@props(['product'])

@php
    $imageModel = $product->primaryImage ?? $product->images?->first();
    $cardImageUrl = $imageModel?->url;

    // Soft pastel container backgrounds seperti pada gambar mockup
    $bgPalette = ['#eef2f7', '#e8f1fc', '#f5f0ff', '#fef9e7', '#f0fdf9', '#fef2f2'];
    $bgCard = $bgPalette[($product->id ?? 0) % count($bgPalette)];

    // Badge kondisi (Seperti Baru, Baik, Layak Pakai)
    $cond = strtolower(trim($product->condition ?? ''));
    if (str_contains($cond, 'seperti') || $cond === 'baru') {
        $badgeColor = 'text-emerald-700';
        $badgeText = 'Seperti Baru';
    } elseif (str_contains($cond, 'layak')) {
        $badgeColor = 'text-slate-600';
        $badgeText = 'Layak Pakai';
    } else {
        $badgeColor = 'text-amber-700';
        $badgeText = 'Baik';
    }
@endphp

<a href="{{ route('products.show', $product->slug) }}"
   class="group bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs hover:shadow-md hover:border-slate-300 transition-all duration-200 flex flex-col">

    {{-- Image Box --}}
    <div style="background-color: {{ $bgCard }};" class="relative overflow-hidden h-44 sm:h-48 flex items-center justify-center p-4">
        @if($cardImageUrl)
            <img src="{{ $cardImageUrl }}"
                 alt="{{ $product->title }}"
                 class="max-h-full max-w-full object-contain group-hover:scale-105 transition-transform duration-300 drop-shadow-xs">
        @else
            <div class="w-full h-full flex items-center justify-center text-slate-300">
                <x-heroicon-o-photo class="w-12 h-12"/>
            </div>
        @endif

        {{-- Floating Condition Badge (z-10 agar selalu terlihat di atas gambar saat hover) --}}
        <span class="absolute top-3 left-3 z-10 bg-white/95 backdrop-blur-xs font-bold text-[11px] px-2.5 py-0.5 rounded-full shadow-xs {{ $badgeColor }} pointer-events-none">
            {{ $badgeText }}
        </span>
    </div>

    {{-- Info Box --}}
    <div class="p-4 flex flex-col flex-1 justify-between bg-white">
        <div>
            <h3 class="font-bold text-slate-900 text-xs sm:text-sm leading-snug line-clamp-1 group-hover:text-indigo-600 transition" title="{{ $product->title }}">
                {{ $product->title }}
            </h3>
            <p class="font-extrabold text-slate-900 text-sm sm:text-base mt-0.5">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </p>

            {{-- COD Location Pill Tag with Target / Bullseye Icon --}}
            <div class="mt-2.5">
                <span class="inline-flex items-center gap-1.5 bg-[#f1f5f9] text-slate-600 text-[11px] font-medium px-2 py-0.5 rounded-md max-w-full">
                    <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="7" stroke-width="2"/>
                        <circle cx="12" cy="12" r="2.5" stroke-width="2"/>
                    </svg>
                    <span class="truncate max-w-[130px]" title="Area COD: {{ $product->cod_area ?: $product->location }}">{{ $product->cod_area ?: $product->location }}</span>
                </span>
            </div>
        </div>

        {{-- Bottom Meta Row: Seller Avatar & Time Ago --}}
        <div class="mt-3.5 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
            <div class="flex items-center gap-1.5 min-w-0">
                <div class="w-4 h-4 rounded-full bg-slate-300 flex-shrink-0"></div>
                <span class="text-xs text-slate-500 font-medium truncate max-w-[90px] sm:max-w-[110px]">
                    {{ $product->seller->name ?? 'Mahasiswa' }}
                </span>
            </div>
            <span class="text-[11px] text-slate-400 whitespace-nowrap flex-shrink-0">
                {{ $product->created_at->diffForHumans() }}
            </span>
        </div>
    </div>
</a>
