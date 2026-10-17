<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Services\CloudinaryService;

class ProductController extends Controller
{
    public function create()
    {
        $categories = Category::getOrderedCategories();
        return view('pages.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|integer|min:0',
            'condition'   => 'required|in:Baru,Bekas Seperti Baru,Bekas Layak Pakai',
            'description' => 'required|string',
            'location'    => 'required|string|max:150',
            'cod_area'    => 'required|string|max:150',
            'images'      => 'required|array|min:1|max:5',
            'images.*'    => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $product = Product::create([
            'seller_id'   => Auth::id(),
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'price'       => $request->price,
            'condition'   => $request->condition,
            'description' => $request->description,
            'location'    => $request->location,
            'cod_area'    => $request->cod_area,
            'status'      => 'pending',
        ]);

        // Proses dan simpan foto produk
        $gdInfo = function_exists('gd_info') ? gd_info() : [];
        $supportsJpeg = !empty($gdInfo['JPEG Support']) && function_exists('imagecreatefromjpeg');
        $supportsWebp = !empty($gdInfo['WebP Support']) && function_exists('imagewebp');
        $supportsPng  = !empty($gdInfo['PNG Support']) && function_exists('imagepng');

        $manager = ($supportsPng || $supportsJpeg) ? new ImageManager(new Driver()) : null;
        $isPrimary = true;

        foreach ($request->file('images') as $imageFile) {
            $mime = $imageFile->getMimeType();
            $canProcessWithGd = ($supportsPng && in_array($mime, ['image/png', 'image/x-png'])) ||
                                ($supportsJpeg && in_array($mime, ['image/jpeg', 'image/jpg', 'image/pjpeg']));

            $storedFilename = null;

            if ($canProcessWithGd && $manager) {
                try {
                    $image = $manager->read($imageFile);

                    if ($image->width() > 1200) {
                        $image->scale(width: 1200);
                    }

                    if ($supportsWebp) {
                        $encoded = $image->toWebp(80);
                        $ext = 'webp';
                    } elseif ($supportsJpeg) {
                        $encoded = $image->toJpeg(85);
                        $ext = 'jpg';
                    } else {
                        $encoded = $image->toPng();
                        $ext = 'png';
                    }

                    // Upload ke Cloudinary jika dikonfigurasi
                    if (CloudinaryService::isConfigured()) {
                        $cloudUrl = CloudinaryService::upload((string) $encoded, 'product_' . uniqid() . '.' . $ext);
                        if ($cloudUrl) {
                            $storedFilename = $cloudUrl;
                        }
                    }

                    if (!$storedFilename) {
                        $filename = 'products/' . uniqid() . '.' . $ext;
                        Storage::disk('public')->put($filename, (string) $encoded);
                        $storedFilename = $filename;
                    }
                } catch (\Throwable) {
                    if (CloudinaryService::isConfigured()) {
                        $storedFilename = CloudinaryService::upload($imageFile);
                    }
                    if (!$storedFilename) {
                        $storedFilename = $imageFile->store('products', 'public');
                    }
                }
            } else {
                if (CloudinaryService::isConfigured()) {
                    $storedFilename = CloudinaryService::upload($imageFile);
                }
                if (!$storedFilename) {
                    $storedFilename = $imageFile->store('products', 'public');
                }
            }

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $storedFilename,
                'is_primary' => $isPrimary,
            ]);

            $isPrimary = false;
        }

        return redirect()->route('products.success')->with('product', $product);
    }

    public function show(string $slug)
    {
        $product = Product::with(['images', 'category', 'seller'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Jika belum disetujui, hanya pemilik barang atau admin yang dapat melihat
        if ($product->status !== 'approved') {
            if (!Auth::check() || (Auth::id() !== $product->seller_id && !Auth::user()->isAdmin())) {
                abort(404);
            }
        }

        return view('pages.products.show', compact('product'));
    }

    public function success()
    {
        return view('pages.products.success');
    }

    public function myListings()
    {
        $products = Product::with(['primaryImage', 'category'])
            ->where('seller_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('pages.products.my-listings', compact('products'));
    }

    public function markAsSold(Product $product)
    {
        if ($product->seller_id !== Auth::id()) {
            abort(403);
        }

        $product->update(['status' => 'sold']);

        return back()->with('success', 'Status iklan berhasil diubah menjadi Terjual.');
    }

    public function destroy(Product $product)
    {
        if ($product->seller_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        foreach ($product->images as $img) {
            if (!filter_var($img->image_path, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($img->image_path);
            }
        }

        $product->delete();

        return back()->with('success', 'Iklan berhasil dihapus.');
    }
}
