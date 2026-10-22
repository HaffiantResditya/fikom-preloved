<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile view (Halaman Profile).
     */
    public function show(Request $request): View
    {
        $user = $request->user();

        $myProducts = $user->products()
            ->with(['primaryImage', 'images', 'category'])
            ->latest()
            ->get();

        $transactions = $myProducts;

        $counts = [
            'all'      => $myProducts->count(),
            'approved' => $myProducts->where('status', 'approved')->count(),
            'pending'  => $myProducts->where('status', 'pending')->count(),
            'rejected' => $myProducts->where('status', 'rejected')->count(),
            'sold'     => $myProducts->where('status', 'sold')->count(),
        ];

        return view('profile.show', [
            'user'         => $user,
            'myProducts'   => $myProducts,
            'transactions' => $transactions,
            'counts'       => $counts,
        ]);
    }

    /**
     * Display the user's profile edit form (Halaman Edit Profile).
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        $myProducts = $user->products()
            ->with(['primaryImage', 'images', 'category'])
            ->latest()
            ->get();

        $transactions = $myProducts;

        $counts = [
            'all'      => $myProducts->count(),
            'approved' => $myProducts->where('status', 'approved')->count(),
            'pending'  => $myProducts->where('status', 'pending')->count(),
            'rejected' => $myProducts->where('status', 'rejected')->count(),
            'sold'     => $myProducts->where('status', 'sold')->count(),
        ];

        return view('profile.edit', [
            'user'         => $user,
            'myProducts'   => $myProducts,
            'transactions' => $transactions,
            'counts'       => $counts,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        $user->fill([
            'name'            => $validated['name'],
            'email'           => $validated['email'],
            'whatsapp_number' => $validated['whatsapp_number'] ?? null,
            'major'           => $validated['major'] ?? null,
            'location'        => $validated['location'] ?? null,
            'bio'             => $validated['bio'] ?? null,
        ]);

        if ($user->isDirty('email')) {
            $user->account_status = 'pending';
        }

        // Handle profile photo upload or removal
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $path = $request->file('profile_photo')->store('profiles', 'public');
            $user->profile_photo = $path;
        } elseif ($request->boolean('remove_photo')) {
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $user->profile_photo = null;
        }

        $user->save();

        return Redirect::route('profile.show')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
