<nav style="background-color: #ffffff; border-bottom: 1px solid #e2e8f0;" class="sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 gap-4">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center hover:opacity-95 transition">
                <x-logo />
            </a>

            {{-- Desktop Search Bar in Navbar --}}
            <form action="{{ route('catalog') }}" method="GET" class="hidden md:flex flex-1 max-w-xl mx-4 lg:mx-8">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari barang kos: kasur, meja, galon..."
                           style="border: 1px solid #cbd5e1 !important;"
                           class="w-full bg-white rounded-lg pl-10 pr-4 py-2 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-slate-400 transition">
                </div>
            </form>

            {{-- Right Actions --}}
            <div class="flex items-center gap-2 sm:gap-4 flex-shrink-0">
                @guest
                    <a href="{{ route('login') }}"
                       class="text-xs sm:text-sm font-semibold text-slate-700 hover:text-black px-2 py-1.5 rounded-lg hover:bg-slate-100 transition whitespace-nowrap">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                       class="hidden sm:inline-block text-xs sm:text-sm font-medium text-slate-700 hover:text-black px-2 py-1.5 rounded-lg hover:bg-slate-100 transition whitespace-nowrap">
                        Daftar
                    </a>
                    <a href="{{ route('login') }}"
                       style="background-color: #0f172a !important; color: #ffffff !important;"
                       class="font-medium text-xs sm:text-sm px-3 sm:px-5 py-1.5 sm:py-2.5 rounded-lg transition whitespace-nowrap shadow-sm hover:opacity-90">
                        Mulai Jual
                    </a>
                @endguest

                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="/admin" class="bg-amber-100 text-amber-800 hover:bg-amber-200 text-xs font-semibold px-2.5 py-1.5 rounded-md transition flex items-center gap-1">
                            <x-heroicon-o-shield-check class="w-3.5 h-3.5"/> Admin
                        </a>
                    @endif

                    {{-- User Dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 text-xs sm:text-sm font-medium text-slate-800 hover:text-black transition">
                            @if(auth()->user()->profile_photo_url)
                                <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-7 h-7 rounded-full object-cover border border-slate-200">
                            @else
                                <div class="w-7 h-7 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs font-bold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <span class="max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-transition
                             class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2.5 z-50 text-sm">
                            @if(auth()->user()->isAdmin())
                                <a href="/admin" class="flex items-center gap-3 px-4 py-2.5 font-semibold text-amber-700 hover:bg-amber-50 rounded-xl mx-1.5 transition">
                                    <span class="text-base">🛡️</span>
                                    <span>Dashboard Admin</span>
                                </a>
                            @endif
                            @if(auth()->user()->isApproved())
                                <a href="{{ route('products.my-listings') }}" class="flex items-center gap-3 px-4 py-2.5 text-slate-700 hover:bg-slate-50 hover:text-slate-900 rounded-xl mx-1.5 transition font-medium">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <span>Iklan Saya</span>
                                </a>
                            @endif
                            <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-4 py-2.5 text-slate-700 hover:bg-slate-50 hover:text-slate-900 rounded-xl mx-1.5 transition font-medium">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Profil Saya</span>
                            </a>
                            <div class="border-t border-slate-100 my-1.5 mx-2"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-3 w-full text-left px-4 py-2.5 text-red-600 hover:bg-red-50 rounded-xl mx-1.5 transition font-medium">
                                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Mulai Jual Button --}}
                    <a href="{{ route('products.create') }}"
                       style="background-color: #0f172a !important; color: #ffffff !important;"
                       class="font-medium text-xs sm:text-sm px-4 sm:px-5 py-2 sm:py-2.5 rounded-lg transition whitespace-nowrap shadow-sm hover:opacity-90">
                        Mulai Jual
                    </a>
                @endauth
            </div>
        </div>

        {{-- Mobile Search Form --}}
        <div class="pb-3 md:hidden">
            <form action="{{ route('catalog') }}" method="GET">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari barang kos: kasur, meja, galon..."
                           style="border: 1px solid #cbd5e1 !important;"
                           class="w-full bg-white rounded-lg pl-10 pr-4 py-2 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-slate-400 transition">
                </div>
            </form>
        </div>
    </div>
</nav>
