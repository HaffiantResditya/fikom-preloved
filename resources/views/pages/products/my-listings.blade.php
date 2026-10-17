<x-app-layout>
    <x-slot name="title">Iklan Saya</x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-extrabold text-gray-800">Iklan Saya</h1>
            <a href="{{ route('products.create') }}"
               class="bg-indigo-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl hover:bg-indigo-700 transition flex items-center gap-2">
                <x-heroicon-o-plus class="w-4 h-4"/> Pasang Iklan Baru
            </a>
        </div>

        @if($products->isEmpty())
            <div class="text-center py-24 text-gray-400">
                <x-heroicon-o-tag class="w-16 h-16 mx-auto mb-3 opacity-30"/>
                <p class="text-lg">Anda belum memiliki iklan.</p>
                <a href="{{ route('products.create') }}" class="text-indigo-500 text-sm mt-2 inline-block hover:underline">Pasang iklan pertama Anda →</a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($products as $product)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">
                        {{-- Thumbnail --}}
                        <div class="w-20 h-20 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                            @if($product->primaryImage)
                                <img src="{{ $product->primaryImage->url }}" alt="{{ $product->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <x-heroicon-o-photo class="w-8 h-8"/>
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-800 truncate">{{ $product->title }}</h3>
                            <p class="text-sm text-gray-500">{{ $product->category->name }} • Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $product->created_at->diffForHumans() }}</p>
                        </div>

                        {{-- Status Badge --}}
                        <div class="flex-shrink-0">
                            @php
                                $statusColors = [
                                    'pending'  => 'bg-yellow-100 text-yellow-700',
                                    'approved' => 'bg-green-100 text-green-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                    'sold'     => 'bg-gray-100 text-gray-600',
                                ];
                                $statusLabels = [
                                    'pending'  => 'Menunggu Review',
                                    'approved' => 'Tayang',
                                    'rejected' => 'Ditolak',
                                    'sold'     => 'Terjual',
                                ];
                            @endphp
                            <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $statusColors[$product->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $statusLabels[$product->status] ?? $product->status }}
                            </span>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-2 flex-shrink-0">
                            @if($product->status === 'approved')
                                <a href="{{ route('products.show', $product->slug) }}"
                                   title="Lihat Iklan"
                                   class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                    <x-heroicon-o-eye class="w-5 h-5"/>
                                </a>
                                <form method="POST" action="{{ route('products.mark-sold', $product) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            onclick="return confirm('Tandai barang ini sebagai sudah terjual?')"
                                            class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-3 py-1.5 rounded-lg transition">
                                        Tandai Terjual
                                    </button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('products.destroy', $product) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        title="Hapus Iklan"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus iklan ini?')"
                                        class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition">
                                    <x-heroicon-o-trash class="w-5 h-5"/>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-8">{{ $products->links() }}</div>
        @endif
    </div>
</x-app-layout>
