<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Konsultasi;
use App\Models\JenisHewan;
use Carbon\Carbon;

class KonsultasiController extends Controller
{
    public function index()
    {
        $consultations = Konsultasi::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('user.konsultasi.index', [
            'consultations' => $consultations,
            'total' => $consultations->count(),
            'pending' => $consultations->where('status', 'pending')->count(),
            'diterima' => $consultations->where('status', 'diterima')->count(),
            'selesai' => $consultations->where('status', 'selesai')->count(),
        ]);
    }

    public function create()
    {
        $jenisHewan = JenisHewan::where('aktif', 'ya')->orderBy('nama')->get();
        return view('user.konsultasi.create', compact('jenisHewan'));
    }

    public function store(Request $request)
    {
        $today = Carbon::today();
        
        $validated = $request->validate([
            'nama_pemilik' => 'required|string|max:100',
            'no_wa' => 'required|string|max:20',
            'jenis_hewan' => 'required|string',
            'topik' => 'required|string|max:100',
            'tanggal_janji' => 'required|date|after_or_equal:today',
            'jam_janji' => 'required',
            'catatan' => 'nullable|string|max:1000',
        ]);

        // Cek Double Booking
        $isBooked = Konsultasi::where('tanggal_janji', $validated['tanggal_janji'])
            ->where('jam_janji', $validated['jam_janji'])
            ->whereIn('status', ['pending', 'diterima'])
            ->exists();

        if ($isBooked) {
            return back()->withErrors(['jam_janji' => 'Maaf, jam ini baru saja dipesan orang lain. Silakan pilih jam lain.'])->withInput();
        }

        try {
            $konsultasi = Konsultasi::create([
                'user_id' => Auth::id(),
                'kode_konsultasi' => $this->generateKodeKonsultasi(),
                'nama_pemilik' => $validated['nama_pemilik'],
                'no_wa' => $this->normalizeWhatsApp($validated['no_wa']),
                'jenis_hewan' => $validated['jenis_hewan'],
                'topik' => $validated['topik'],
                'tanggal_janji' => $validated['tanggal_janji'],
                'jam_janji' => $validated['jam_janji'],
                'catatan' => $validated['catatan'],
                'status' => 'pending',
            ]);

            return redirect()->route('user.konsultasi.index')
                ->with('success', "Booking Berhasil! Kode: <strong>{$konsultasi->kode_konsultasi}</strong>. Silakan datang sesuai jadwal.");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()])->withInput();
        }
    }

    public function getJam(Request $request)
    {
        $jamTerpakai = Konsultasi::where('tanggal_janji', $request->tanggal)
            ->whereIn('status', ['pending', 'diterima'])
            ->pluck('jam_janji')
            ->toArray();
            
        return response()->json($jamTerpakai);
    }

    private function normalizeWhatsApp($nomor)
    {
        $nomor = preg_replace('/[^\d]/', '', $nomor);
        if (str_starts_with($nomor, '0')) return '62' . substr($nomor, 1);
        return $nomor;
    }

    private function generateKodeKonsultasi()
    {
        $prefix = 'KONS-' . date('Y');
        $count = Konsultasi::where('kode_konsultasi', 'like', "$prefix%")->count();
        return $prefix . '-' . sprintf('%04d', $count + 1);
    }
}