<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $pendingCount    = Product::where('status', 'pending')->count();
        $approvedCount   = Product::where('status', 'approved')->count();
        $soldCount       = Product::where('status', 'sold')->count();
        $soldTotalAmount = Product::where('status', 'sold')->sum('price');
        $activeSellers   = User::where('role', 'student')->where('account_status', 'approved')->count();

        $pendingProducts = Product::with(['seller', 'category', 'primaryImage'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $latestProducts = Product::with(['seller', 'category', 'primaryImage'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'pendingCount',
            'approvedCount',
            'soldCount',
            'soldTotalAmount',
            'activeSellers',
            'pendingProducts',
            'latestProducts'
        ));
    }

    public function approve(Product $product)
    {
        $product->update(['status' => 'approved']);
        return back()->with('success', "Barang '{$product->title}' berhasil disetujui dan tayang di katalog!");
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

        return back()->with('success', "Barang '{$product->title}' telah ditolak.");
    }
}
