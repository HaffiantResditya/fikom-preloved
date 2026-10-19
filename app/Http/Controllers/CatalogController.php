<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::getOrderedCategories();

        $selectedCategorySlug = $request->input('category');

        if ($selectedCategorySlug && $selectedCategorySlug !== 'semua') {
            $currentCategory = Category::where('slug', $selectedCategorySlug)->first();
            if (!$currentCategory) {
                $selectedCategorySlug = 'semua';
            }
        } else {
            $selectedCategorySlug = 'semua';
        }

        if ($selectedCategorySlug === 'semua') {
            $currentCategory = (object) [
                'name' => 'Semua Produk',
                'description' => 'Temukan seluruh barang preloved berkualitas dari sesama mahasiswa FIKOM.',
                'icon_meta' => [
                    'bg' => 'background-color: #f1f5f9; color: #0f172a;',
                    'svg' => '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>',
                ],
            ];
        }

        $query = Product::with(['primaryImage', 'images', 'category', 'seller'])
            ->where('status', 'approved');

        // Filter berdasarkan kategori jika bukan 'semua'
        if ($selectedCategorySlug !== 'semua' && $currentCategory instanceof Category) {
            $query->where('category_id', $currentCategory->id);
        }

        // Search kata kunci
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filter harga minimum
        if ($request->filled('min_price')) {
            $min = (int) preg_replace('/[^0-9]/', '', $request->min_price);
            if ($min > 0) {
                $query->where('price', '>=', $min);
            }
        }

        // Filter harga maksimum
        if ($request->filled('max_price')) {
            $max = (int) preg_replace('/[^0-9]/', '', $request->max_price);
            if ($max > 0) {
                $query->where('price', '<=', $max);
            }
        }

        // Filter kondisi barang (Seperti Baru, Baik, Layak Pakai)
        if ($request->filled('condition')) {
            $cond = strtolower(trim($request->condition));
            if (str_contains($cond, 'seperti') || $cond === 'baru') {
                $query->whereIn('condition', ['Seperti Baru', 'Seperti baru', 'Bekas Seperti Baru', 'Baru']);
            } elseif (str_contains($cond, 'layak')) {
                $query->whereIn('condition', ['Layak Pakai', 'Layak pakai', 'Bekas Layak Pakai']);
            } else {
                $query->whereIn('condition', ['Baik', 'baik']);
            }
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        // 6 produk per halaman sesuai mockup "Menampilkan 1-6"
        $products = $query->paginate(6)->withQueryString();

        $highestProductPrice = (int) Product::where('status', 'approved')->max('price');
        $maxCatalogPrice = max(20000000, (int) (ceil($highestProductPrice / 1000000) * 1000000));

        return view('pages.catalog', compact(
            'products',
            'categories',
            'selectedCategorySlug',
            'currentCategory',
            'sort',
            'maxCatalogPrice'
        ));
    }
}
