<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminSoldController extends Controller
{
    /**
     * Tampilkan halaman Audit Barang Terjual.
     */
    public function index(Request $request)
    {
        $searchQuery = $request->query('search');

        // Query khusus barang yang sudah berstatus terjual ('sold')
        $query = Product::with(['seller', 'category', 'primaryImage', 'images'])
            ->where('status', 'sold');

        // Filter Pencarian
        if (!empty($searchQuery)) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('title', 'like', "%{$searchQuery}%")
                  ->orWhere('description', 'like', "%{$searchQuery}%")
                  ->orWhere('location', 'like', "%{$searchQuery}%")
                  ->orWhere('cod_area', 'like', "%{$searchQuery}%")
                  ->orWhereHas('seller', function ($sellerQuery) use ($searchQuery) {
                      $sellerQuery->where('name', 'like', "%{$searchQuery}%")
                                  ->orWhere('email', 'like', "%{$searchQuery}%");
                  });
            });
        }

        $soldProducts = $query->latest('updated_at')->paginate(12)->withQueryString();

        // Metrik Ringkasan Transaksi
        $totalSoldCount  = Product::where('status', 'sold')->count();
        $totalSoldAmount = Product::where('status', 'sold')->sum('price');
        $averagePrice    = Product::where('status', 'sold')->avg('price') ?? 0;

        // Alias tambahan untuk kompatibilitas
        $totalTransactions  = $totalSoldCount;
        $totalTurnover      = $totalSoldAmount;
        $activeSellersCount = Product::where('status', 'sold')->distinct('seller_id')->count('seller_id');

        return view('admin.sold.index', compact(
            'soldProducts',
            'searchQuery',
            'totalSoldCount',
            'totalSoldAmount',
            'averagePrice',
            'totalTransactions',
            'totalTurnover',
            'activeSellersCount'
        ));
    }

    /**
     * Kembalikan status barang ke aktif/approved jika salah ditandai terjual.
     */
    public function reactivate(Product $product)
    {
        $product->update(['status' => 'approved']);

        return back()->with('success', "Barang '{$product->title}' berhasil diaktifkan kembali ke etalase.");
    }
}
