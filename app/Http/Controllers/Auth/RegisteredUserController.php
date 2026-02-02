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
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => [
                'required', 
                'string', 
                'max:255', 
                'unique:users',
                'regex:/^[a-zA-Z0-9_]+$/'
            ],
            'email' => [
                'required', 
                'string', 
                'lowercase', 
                'email', 
                'max:255', 
                'unique:'.User::class
            ],
            'password' => [
                'required', 
                'confirmed', 
                Rules\Password::defaults(),
                'min:6'
            ],
        ], [
            'username.required' => 'Username wajib diisi!',
            'username.unique' => 'Username sudah terdaftar!',
            'username.regex' => 'Username hanya boleh berisi huruf, angka, dan underscore!',
            'email.required' => 'Email wajib diisi!',
            'email.email' => 'Format email tidak valid!',
            'email.unique' => 'Email sudah terdaftar!',
            'password.required' => 'Password wajib diisi!',
            'password.min' => 'Password minimal 6 karakter!',
            'password.confirmed' => 'Password dan konfirmasi tidak sama!',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'nomor_wa' => null,
        ]);

        event(new Registered($user));

        // TIDAK login otomatis, redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan login.');
    }
}