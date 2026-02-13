<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil dokter
     */
    public function index()
    {
        $user = Auth::user();
        return view('dokter.profile.index', compact('user'));
    }

    /**
     * Tampilkan form edit profil
     */
    public function edit()
    {
        $user = Auth::user();
        return view('dokter.profile.edit', compact('user'));
    }

    /**
     * Update profil dokter (termasuk foto)
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'username'  => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'nomor_wa'  => 'nullable|string|max:20',
            'foto'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'current_password' => 'nullable|required_with:new_password|current_password',
            'new_password'     => 'nullable|min:8|confirmed',
        ]);

        try {
            $user->username = $validated['username'];
            $user->email    = $validated['email'];
            $user->nomor_wa = $validated['nomor_wa'] ?? $user->nomor_wa;

            // Upload foto
            if ($request->hasFile('foto')) {
                if ($user->foto) {
                    Storage::disk('public')->delete('foto_dokter/' . $user->foto);
                }

                $file = $request->file('foto');
                $filename = 'dokter_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('foto_dokter', $filename, 'public');
                $user->foto = $filename;
            }

            // Update password
            if ($request->filled('new_password')) {
                $user->password = Hash::make($validated['new_password']);
            }

            $user->save();

            return redirect()->route('dokter.profile.index')
                ->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal memperbarui profil: ' . $e->getMessage()]);
        }
    }
}