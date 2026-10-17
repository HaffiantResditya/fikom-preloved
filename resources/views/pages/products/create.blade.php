<x-app-layout>
    <x-slot name="title">Jual Barang</x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-3xl font-extrabold text-gray-800 mb-2">Pasang Iklan Barang</h1>
        <p class="text-gray-500 mb-8">Iklan akan direview oleh Admin sebelum tayang.</p>

        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data"
              class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 space-y-6">
            @csrf

            {{-- Judul --}}
            <div>
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-1.5">Judul Iklan <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required maxlength="150"
                       placeholder="Cth: Laptop Asus VivoBook 14 Core i5..."
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('title') border-red-400 @enderror">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Kategori & Kondisi --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                    <select name="category_id" id="category_id" required
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('category_id') border-red-400 @enderror">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="condition" class="block text-sm font-semibold text-gray-700 mb-1.5">Kondisi <span class="text-red-500">*</span></label>
                    <select name="condition" id="condition" required
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('condition') border-red-400 @enderror">
                        <option value="">Pilih Kondisi</option>
                        <option value="Baru" {{ old('condition') === 'Baru' ? 'selected' : '' }}>Baru</option>
                        <option value="Bekas Seperti Baru" {{ old('condition') === 'Bekas Seperti Baru' ? 'selected' : '' }}>Bekas Seperti Baru</option>
                        <option value="Bekas Layak Pakai" {{ old('condition') === 'Bekas Layak Pakai' ? 'selected' : '' }}>Bekas Layak Pakai</option>
                    </select>
                    @error('condition') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Harga --}}
            <div>
                <label for="price" class="block text-sm font-semibold text-gray-700 mb-1.5">Harga (Rp) <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium">Rp</span>
                    <input type="number" name="price" id="price" value="{{ old('price') }}" min="0" required
                           placeholder="0"
                           class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('price') border-red-400 @enderror">
                </div>
                @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi <span class="text-red-500">*</span></label>
                <textarea name="description" id="description" rows="5" required
                          placeholder="Ceritakan kondisi barang, alasan dijual, dll..."
                          class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 resize-none @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Lokasi & Area COD --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="location" class="block text-sm font-semibold text-gray-700 mb-1.5">Lokasi Penjual <span class="text-red-500">*</span></label>
                    <input type="text" name="location" id="location" value="{{ old('location') }}" required maxlength="150"
                           placeholder="Cth: Kos Jalan Raya Palur"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('location') border-red-400 @enderror">
                    @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="cod_area" class="block text-sm font-semibold text-gray-700 mb-1.5">Area COD <span class="text-red-500">*</span></label>
                    <input type="text" name="cod_area" id="cod_area" value="{{ old('cod_area') }}" required maxlength="150"
                           placeholder="Cth: Area Kampus FIKOM, Kantin"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('cod_area') border-red-400 @enderror">
                    @error('cod_area') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Upload Foto --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Foto Barang <span class="text-red-500">*</span>
                    <span class="text-xs text-gray-400 font-normal ml-1">(Maks. 5 foto, format JPG/PNG/WebP)</span>
                </label>
                <div class="border-2 border-dashed border-gray-200 rounded-2xl p-6 text-center hover:border-indigo-300 transition"
                     x-data="{ previews: [] }"
                     @dragover.prevent @drop.prevent="
                        const files = $event.dataTransfer.files;
                        if (files.length) {
                            $refs.fileInput.files = files;
                            $refs.fileInput.dispatchEvent(new Event('change'));
                        }">
                    <input type="file" name="images[]" id="images" multiple accept="image/*" required x-ref="fileInput"
                           class="hidden"
                           @change="
                                previews = [];
                                Array.from($event.target.files).slice(0, 5).forEach(f => {
                                    const r = new FileReader();
                                    r.onload = e => previews.push(e.target.result);
                                    r.readAsDataURL(f);
                                })">
                    <template x-if="previews.length === 0">
                        <div>
                            <x-heroicon-o-camera class="w-10 h-10 mx-auto text-gray-300 mb-2"/>
                            <p class="text-sm text-gray-400">Klik atau seret foto ke sini</p>
                            <button type="button" @click="$refs.fileInput.click()"
                                    class="mt-3 text-sm text-indigo-600 font-medium hover:underline">Pilih Foto</button>
                        </div>
                    </template>
                    <template x-if="previews.length > 0">
                        <div>
                            <div class="grid grid-cols-3 sm:grid-cols-5 gap-2 mb-3">
                                <template x-for="(src, i) in previews" :key="i">
                                    <div class="aspect-square rounded-xl overflow-hidden border border-gray-200">
                                        <img :src="src" class="w-full h-full object-cover">
                                    </div>
                                </template>
                            </div>
                            <button type="button" @click="$refs.fileInput.click()"
                                    class="text-sm text-indigo-600 hover:underline">Ganti Foto</button>
                        </div>
                    </template>
                </div>
                @error('images') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                @error('images.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" id="submit-listing"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-2xl transition shadow-lg text-base">
                Pasang Iklan Sekarang
            </button>
        </form>
    </div>
</x-app-layout>
