<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->query('status', 'all');
        $searchQuery  = $request->query('search');

        $query = User::where('role', 'student')
            ->withCount('products')
            ->with(['products.primaryImage', 'products.images', 'products.category']);

        if ($statusFilter !== 'all') {
            if ($statusFilter === 'suspended') {
                $query->whereIn('account_status', ['suspended', 'rejected']);
            } elseif (in_array($statusFilter, ['approved', 'pending'])) {
                $query->where('account_status', $statusFilter);
            }
        }

        if (!empty($searchQuery)) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('name', 'like', "%{$searchQuery}%")
                  ->orWhere('email', 'like', "%{$searchQuery}%")
                  ->orWhere('whatsapp_number', 'like', "%{$searchQuery}%");
            });
        }

        $users = $query->latest()->paginate(12)->withQueryString();

        $totalUsers    = User::where('role', 'student')->count();
        $approvedUsers = User::where('role', 'student')->where('account_status', 'approved')->count();
        $pendingUsers  = User::where('role', 'student')->where('account_status', 'pending')->count();
        $suspendedUsers= User::where('role', 'student')->whereIn('account_status', ['suspended', 'rejected'])->count();

        return view('admin.users.index', compact(
            'users', 'statusFilter', 'searchQuery', 'totalUsers',
            'approvedUsers', 'pendingUsers', 'suspendedUsers'
        ));
    }

    public function approve(User $user)
    {
        $user->update(['account_status' => 'approved']);
        return back()->with('success', "Akun mahasiswa '{$user->name}' berhasil diverifikasi dan aktif!");
    }

    public function suspend(Request $request, User $user)
    {
        $user->update(['account_status' => 'rejected']);
        $reason = $request->input('reason');
        $msg = "Akun mahasiswa '{$user->name}' telah dinonaktifkan.";
        if ($reason) {
            $msg .= " (Alasan: {$reason})";
        }
        return back()->with('success', $msg);
    }

    public function destroy(User $user)
    {
        $name = $user->name;
        $user->delete();
        return back()->with('success', "Akun '{$name}' berhasil dihapus dari sistem.");
    }
}
