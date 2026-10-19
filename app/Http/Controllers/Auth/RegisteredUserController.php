<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     * Hanya menerima email domain kampus resmi.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'             => ['required', 'string', 'max:100'],
            'email'            => [
                'required', 'string', 'lowercase', 'email', 'max:100',
                'unique:' . User::class,
                function ($attribute, $value, $fail) {
                    // Validasi domain email kampus hanya @mhs.udb.ac.id
                    $allowedDomains = [
                        'mhs.udb.ac.id',
                    ];
                    $emailDomain = strtolower(substr(strrchr($value, '@'), 1));
                    if (!in_array($emailDomain, $allowedDomains)) {
                        $fail('Hanya email resmi kampus (@mhs.udb.ac.id) yang diizinkan mendaftar.');
                    }
                },
            ],
            'whatsapp_number'  => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'password'         => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'             => $request->name,
            'email'            => $request->email,
            'password'         => Hash::make($request->password),
            'whatsapp_number'  => $request->whatsapp_number,
            'role'             => 'student',
            'account_status'   => 'approved',
        ]);

        event(new Registered($user));

        Auth::login($user);

        $request->session()->regenerate();

        // Redirect ke halaman beranda/home setelah berhasil mendaftar
        return redirect()->route('home')->with('success', 'Pendaftaran berhasil! Akun Anda telah aktif dan dapat langsung bertransaksi.');
    }
}
