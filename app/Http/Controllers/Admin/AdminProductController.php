<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter   = $request->query('status', 'all');
        $searchQuery    = $request->query('search');
        $categoryFilter = $request->query('kategori');
        $sortBy         = $request->query('urutkan', 'terbaru');

        $query = Product::with(['seller', 'category', 'primaryImage', 'images']);

        if ($statusFilter !== 'all' && in_array($statusFilter, ['pending', 'approved', 'rejected', 'sold'])) {
            $query->where('status', $statusFilter);
        }

        if (!empty($searchQuery)) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('title', 'like', "%{$searchQuery}%")
                  ->orWhere('description', 'like', "%{$searchQuery}%")
                  ->orWhereHas('seller', function ($sq) use ($searchQuery) {
                      $sq->where('name', 'like', "%{$searchQuery}%")
                         ->orWhere('email', 'like', "%{$searchQuery}%");
                  });
            });
        }

        if (!empty($categoryFilter)) {
            $query->where('category_id', $categoryFilter);
        }

        switch ($sortBy) {
            case 'terlama':    $query->oldest(); break;
            case 'harga-asc':  $query->orderBy('price', 'asc'); break;
            case 'harga-desc': $query->orderBy('price', 'desc'); break;
            default:           $query->latest(); break;
        }

        $products = $query->paginate(12)->withQueryString();

        $allCount      = Product::count();
        $pendingCount  = Product::where('status', 'pending')->count();
        $approvedCount = Product::where('status', 'approved')->count();
        $rejectedCount = Product::where('status', 'rejected')->count();
        $categories    = Category::all();

        return view('admin.products.index', compact(
            'products', 'statusFilter', 'searchQuery', 'categoryFilter',
            'sortBy', 'allCount', 'pendingCount', 'approvedCount',
            'rejectedCount', 'categories'
        ));
    }

    public function approve(Product $product)
    {
        $product->update(['status' => 'approved']);
        return back()->with('success', "Barang '{$product->title}' berhasil disetujui dan aktif di katalog!");
    }

    public function reject(Request $request, Product $product)
    {
        $alasan  = $request->input('alasan', 'Tidak memenuhi standar kurasi');
        $catatan = $request->input('catatan');

        $fullReason = $alasan;
        if (!empty($catatan)) {
            $fullReason .= ' — ' . $catatan;
        }

        $product->update([
            'status'           => 'rejected',
            'rejection_reason' => $fullReason,
        ]);

        return back()->with('success', "Barang '{$product->title}' telah ditolak. Alasan: {$fullReason}");
    }

    public function destroy(Product $product)
    {
        $title = $product->title;
        $product->delete();
        return back()->with('success', "Barang '{$title}' berhasil dihapus dari sistem.");
    }
}
