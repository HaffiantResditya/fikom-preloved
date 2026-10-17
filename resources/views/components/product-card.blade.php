@props(['product'])

<a href="{{ route('products.show', $product->slug) }}"
   style="border: 1px solid #e2e8f0 !important; background-color: #ffffff !important;"
   class="group rounded-xl overflow-hidden shadow-sm hover:shadow-md hover:border-slate-300 transition-all duration-200 flex flex-col">

    {{-- Image Box --}}
    <div class="relative overflow-hidden h-36 sm:h-40 bg-slate-100">
        @php
            $imageModel = $product->primaryImage ?? $product->images?->first();
            $cardImageUrl = $imageModel?->url;
        @endphp
        @if($cardImageUrl)
            <img src="{{ $cardImageUrl }}"
                 alt="{{ $product->title }}"
                 class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-300">
        @else
            <div class="w-full h-full flex items-center justify-center text-slate-300">
                <x-heroicon-o-photo class="w-12 h-12"/>
            </div>
        @endif
    </div>

    {{-- Info Box --}}
    <div class="p-3.5 flex flex-col flex-1 justify-between">
        <div>
            <h3 class="font-bold text-slate-900 text-xs sm:text-sm leading-snug line-clamp-1 group-hover:text-indigo-600 transition" title="{{ $product->title }}">
                {{ $product->title }}
            </h3>
            <p class="font-extrabold text-slate-900 text-xs sm:text-sm mt-1">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </p>
        </div>

        {{-- COD Area Pill Tag with Truck Icon --}}
        <div class="mt-2.5">
            <span style="background-color: #f1f5f9; color: #475569;" class="inline-flex items-center gap-1.5 text-[11px] font-semibold px-2 py-0.5 rounded">
                <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                </svg>
                <span class="truncate max-w-[130px]" title="Area COD: {{ $product->cod_area ?: $product->location }}">{{ $product->cod_area ?: $product->location }}</span>
            </span>
        </div>
    </div>
</a>
