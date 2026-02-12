<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil user
     */
    public function index()
    {
        $user = Auth::user();
        return view('user.index', compact('user'));
    }

    /**
     * Tampilkan form edit profil
     */
    public function edit()
    {
        $user = Auth::user();
        return view('user.edit', compact('user'));
    }

    /**
     * Update data profil (termasuk foto)
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'email'    => 'nullable|email|unique:users,email,' . $user->id,
            'nomor_wa' => 'nullable|string|max:20',
            'foto'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->email    = $request->email;
        $user->nomor_wa = $request->nomor_wa;

        // Upload foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto) {
                Storage::disk('public')->delete('foto_user/' . $user->foto);
            }

            $file = $request->file('foto');
            $filename = 'user_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('foto_user', $filename, 'public');
            $user->foto = $filename;
        }

        $user->save();

        return redirect()->route('user.profil.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}