<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Konsultasi;
use App\Models\KonsultasiBalasan;
use App\Models\JenisHewan;
use Carbon\Carbon;

class KonsultasiController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $jenisHewan = JenisHewan::where('aktif', 'ya')->orderBy('nama')->get();
        
        return view('user.konsultasi.create', compact('user', 'jenisHewan'));
    }
    
    public function store(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        $validated = $request->validate([
            'nama_pemilik' => 'required|string|max:100',
            'no_wa' => 'required|string|max:20',
            'jenis_hewan' => 'required|string|max:50',
            'topik' => 'required|string|max:100',
            'tanggal_janji' => 'required|date|after_or_equal:' . $today->format('Y-m-d'),
            'jam_janji' => 'required|date_format:H:i',
            'catatan' => 'nullable|string|max:1000',
        ]);
        
        // Validasi jam konsultasi (08:00 - 18:00)
        $jamInt = (int)explode(':', $validated['jam_janji'])[0];
        if ($jamInt < 8 || $jamInt > 18) {
            return back()->withErrors(['jam_janji' => 'Jam konsultasi hanya tersedia antara 08.00 – 18.00 WIB.'])->withInput();
        }
        
        // Validasi jika hari ini dan jam sudah lewat
        if ($validated['tanggal_janji'] == $today->format('Y-m-d') && $jamInt <= $today->hour) {
            return back()->withErrors(['jam_janji' => 'Jam yang dipilih sudah lewat untuk hari ini.'])->withInput();
        }
        
        // Normalisasi nomor WhatsApp
        $wa_bersih = $this->normalizeWhatsApp($validated['no_wa']);
        if (strlen($wa_bersih) < 10 || strlen($wa_bersih) > 15) {
            return back()->withErrors(['no_wa' => 'Nomor WhatsApp tidak valid. Gunakan format: 081234567890.'])->withInput();
        }
        
        // Cek apakah jam sudah dipesan
        $jamTerpakai = Konsultasi::where('tanggal_janji', $validated['tanggal_janji'])
            ->where('jam_janji', $validated['jam_janji'])
            ->whereIn('status', ['pending', 'diterima'])
            ->exists();
            
        if ($jamTerpakai) {
            return back()->withErrors(['jam_janji' => 'Jam ini sudah dipesan, silakan pilih jam lain.'])->withInput();
        }
        
        // Generate kode konsultasi
        $kode_konsultasi = $this->generateKodeKonsultasi();
        
        try {
            $konsultasi = Konsultasi::create([
                'user_id' => $user->id,
                'kode_konsultasi' => $kode_konsultasi,
                'nama_pemilik' => $validated['nama_pemilik'],
                'no_wa' => $wa_bersih,
                'jenis_hewan' => $validated['jenis_hewan'],
                'topik' => $validated['topik'],
                'tanggal_janji' => $validated['tanggal_janji'],
                'jam_janji' => $validated['jam_janji'],
                'catatan' => $validated['catatan'],
                'status' => 'pending',
            ]);
            
            return redirect()->route('user.konsultasi.index')
                ->with('success', "Konsultasi berhasil dikirim! Kode Anda: <strong>$kode_konsultasi</strong>. Kami akan segera hubungi Anda via WhatsApp.");
                
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menyimpan konsultasi: ' . $e->getMessage()])->withInput();
        }
    }
    
    public function index()
    {
        $user = Auth::user();
        
        $consultations = Konsultasi::where('user_id', $user->id)
            ->with(['dokter', 'balasan'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Hitung statistik
        $total = $consultations->count();
        $pending = $consultations->where('status', 'pending')->count();
        $diterima = $consultations->where('status', 'diterima')->count();
        $selesai = $consultations->where('status', 'selesai')->count();
        
        return view('user.konsultasi.index', compact('user', 'consultations', 'total', 'pending', 'diterima', 'selesai'));
    }
    
    public function getJam(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
        ]);
        
        $jamTerpakai = Konsultasi::where('tanggal_janji', $request->tanggal)
            ->whereIn('status', ['pending', 'diterima'])
            ->pluck('jam_janji')
            ->map(function ($jam) {
                return date('H:i', strtotime($jam));
            })
            ->toArray();
            
        return response()->json($jamTerpakai);
    }
    
    public function balas(Request $request)
    {
        $request->validate([
            'konsultasi_id' => 'required|exists:konsultasi,id',
            'isi' => 'required|string|max:1000',
            'kode' => 'required|string',
        ]);

        $konsultasi = Konsultasi::where('id', $request->konsultasi_id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$konsultasi) {
            return back()->with('error', 'Konsultasi tidak ditemukan.');
        }

        // Simpan balasan
        KonsultasiBalasan::create([
            'konsultasi_id' => $konsultasi->id,
            'pengirim' => 'user',
            'isi' => $request->isi,
            'dibaca_user' => 1, // Karena user yang kirim, otomatis dibaca
        ]);

        return redirect()->route('user.cek-status.show', ['kode' => $request->kode])
            ->with('success', 'Balasan berhasil dikirim.');
    }
    
    private function normalizeWhatsApp($nomor)
    {
        if (empty($nomor)) {
            return '';
        }
        
        $nomor = preg_replace('/[^\d]/', '', $nomor);
        
        if (substr($nomor, 0, 1) === '0') {
            $nomor = '62' . substr($nomor, 1);
        } elseif (substr($nomor, 0, 2) !== '62') {
            $nomor = '62' . $nomor;
        }
        
        return $nomor;
    }
    
    private function generateKodeKonsultasi()
    {
        $tahun = date('Y');
        
        do {
            $last = Konsultasi::where('kode_konsultasi', 'like', "KONS-$tahun-%")
                ->orderBy('kode_konsultasi', 'desc')
                ->first();
                
            $num = $last ? ((int)substr($last->kode_konsultasi, -4) + 1) : 1;
            $kode = 'KONS-' . $tahun . '-' . sprintf('%04d', $num);
            
            $exists = Konsultasi::where('kode_konsultasi', $kode)->exists();
        } while ($exists);
        
        return $kode;
    }
}