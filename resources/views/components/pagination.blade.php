@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center gap-1.5 mt-8">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="w-9 h-9 rounded-lg border border-slate-200 bg-white/60 text-slate-300 flex items-center justify-center cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="w-9 h-9 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 flex items-center justify-center transition shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="w-9 h-9 flex items-center justify-center text-xs text-slate-400">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="w-9 h-9 rounded-lg bg-black text-white text-xs font-bold flex items-center justify-center shadow-xs">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="w-9 h-9 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs font-medium flex items-center justify-center transition shadow-xs">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="w-9 h-9 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 flex items-center justify-center transition shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @else
            <span class="w-9 h-9 rounded-lg border border-slate-200 bg-white/60 text-slate-300 flex items-center justify-center cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        @endif
    </nav>
@endif
