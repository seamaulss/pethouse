<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\Notification;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    // Explicitly define the table name since it's not plural
    protected $table = 'booking';

    protected $fillable = [
        'user_id',
        'jenis_hewan_id',
        'layanan_id',
        'kode_booking',
        'nama_pemilik',
        'email',
        'nomor_wa',
        'nama_hewan',
        'jenis_hewan',
        'ukuran_hewan',
        'tanggal_masuk',
        'tanggal_keluar',
        'dp_dibayar',
        'bukti_dp',
        'catatan',
        'status',
        'petugas_id',
        'alasan_perpanjangan',
        'alasan_cancel',
        'tanggal_perpanjangan',
        'total_harga',

    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_keluar' => 'date',
        'tanggal_perpanjangan' => 'date',
    ];

    // Relationships
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function dailyLogs()
    {
        return $this->hasMany(DailyLog::class, 'booking_id');
    }

    /**
     * Get jenis hewan model relation
     */
    public function jenisHewan()
    {
        return $this->belongsTo(JenisHewan::class, 'jenis_hewan_id');
    }

    protected static function boot()
    {
        parent::boot();

        // Saat booking dibuat
        static::created(function ($booking) {
            Notification::createForAdmin(
                'Booking Baru',
                'Booking baru dibuat oleh ' . $booking->nama_pemilik .
                    ' untuk ' . $booking->nama_hewan . ' (Kode: ' . $booking->kode_booking . ')',
                $booking->id,
                'booking'
            );
        });

        // Saat booking diupdate
        static::updated(function ($booking) {
            $oldStatus = $booking->getOriginal('status');
            $newStatus = $booking->status;

            // Jika status berubah menjadi dibatalkan
            if ($newStatus === 'pembatalan' && $oldStatus !== 'pembatalan') {
                Notification::createForAdmin(
                    'Booking Dibatalkan',
                    'Booking ' . $booking->kode_booking .
                        ' dibatalkan oleh ' . $booking->nama_pemilik,
                    $booking->id,
                    'cancel'
                );
            }

            // Jika tanggal keluar berubah (perpanjangan)
            if ($booking->isDirty('tanggal_keluar')) {
                $oldDate = $booking->getOriginal('tanggal_keluar');
                $newDate = $booking->tanggal_keluar;

                // Cek jika tanggal keluar lebih lama (perpanjangan)
                if ($newDate > $oldDate) {
                    Notification::createForAdmin(
                        'Booking Diperpanjang',
                        'Booking ' . $booking->kode_booking .
                            ' diperpanjang oleh ' . $booking->nama_pemilik,
                        $booking->id,
                        'extend'
                    );
                }
            }

            // Jika status berubah (selain dari/tujuan dibatalkan)
            if ($oldStatus !== $newStatus && $newStatus !== 'pembatalan') {
                Notification::createForAdmin(
                    'Status Booking Berubah',
                    'Status booking ' . $booking->kode_booking .
                        ' berubah dari ' . $oldStatus . ' menjadi ' . $newStatus,
                    $booking->id,
                    'status'
                );
            }
        });
    }

    // Accessors
    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'diterima' => 'Diterima',
            'in_progress' => 'Dititipkan',
            'selesai' => 'Selesai',
            'pembatalan' => 'Dibatalkan',
            'perpanjangan' => 'Perpanjangan Diajukan',
            default => ucfirst($this->status)
        };
    }

    public function getStatusClassAttribute()
    {
        return match ($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'diterima' => 'bg-blue-100 text-blue-800',
            'in_progress' => 'bg-teal-100 text-teal-800',
            'selesai' => 'bg-green-100 text-green-800',
            'pembatalan' => 'bg-red-100 text-red-800',
            'perpanjangan' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Check if booking can be cancelled
     */
    public function canCancel()
    {
        return in_array($this->status, ['pending', 'diterima']);
    }

    /**
     * Check if booking can be extended
     */
    public function canExtend()
    {
        return in_array($this->status, ['diterima', 'in_progress'])
            && $this->tanggal_keluar >= now()->format('Y-m-d');
    }

    /**
     * Get the URL for bukti DP
     */
    public function getBuktiDpUrlAttribute()
    {
        if (!$this->bukti_dp) {
            return null;
        }

        $file = $this->bukti_dp;

        // Cek beberapa lokasi yang mungkin
        $paths = [
            'storage/bukti_dp/' . $file,
            'public/storage/bukti_dp/' . $file,
            'assets/img/bukti_dp/' . $file,
        ];

        foreach ($paths as $path) {
            if (file_exists(public_path($path))) {
                return asset($path);
            }
        }

        // Coba dengan Storage facade
        if (Storage::exists('public/bukti_dp/' . $file)) {
            return Storage::url('bukti_dp/' . $file);
        }

        return null;
    }

    /**
     * Check if bukti DP file exists
     */
    public function getBuktiDpExistsAttribute()
    {
        if (!$this->bukti_dp) {
            return false;
        }

        $paths = [
            public_path('storage/bukti_dp/' . $this->bukti_dp),
            public_path('assets/img/bukti_dp/' . $this->bukti_dp),
            storage_path('app/public/bukti_dp/' . $this->bukti_dp),
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get harga per hari for this booking - PERBAIKAN
     */
    public function getHargaPerHariAttribute()
    {
        // Jika ada jenis_hewan_id, gunakan itu
        if ($this->jenis_hewan_id) {
            $harga = \Illuminate\Support\Facades\DB::table('layanan_harga')
                ->where('layanan_id', $this->layanan_id)
                ->where('jenis_hewan_id', $this->jenis_hewan_id)
                ->value('harga_per_hari');

            return $harga ?? 0;
        }

        // Jika tidak ada jenis_hewan_id, cari berdasarkan string jenis_hewan
        $jenisHewan = JenisHewan::where('nama', $this->jenis_hewan)->first();

        if (!$jenisHewan) {
            return 0;
        }

        $harga = \Illuminate\Support\Facades\DB::table('layanan_harga')
            ->where('layanan_id', $this->layanan_id)
            ->where('jenis_hewan_id', $jenisHewan->id)
            ->value('harga_per_hari');

        return $harga ?? 0;
    }

    /**
     * Get total biaya booking
     */
    public function getTotalBiayaAttribute()
    {
        $hargaPerHari = $this->harga_per_hari;

        if ($hargaPerHari <= 0) {
            return 0;
        }

        // Hitung jumlah hari
        $masuk = Carbon::parse($this->tanggal_masuk);
        $keluar = Carbon::parse($this->tanggal_keluar);
        $durasi = $masuk->diffInDays($keluar);

        return $hargaPerHari * $durasi;
    }

    /**
     * Get durasi booking dalam hari
     */
    public function getDurasiAttribute()
    {
        $masuk = Carbon::parse($this->tanggal_masuk);
        $keluar = Carbon::parse($this->tanggal_keluar);
        return $masuk->diffInDays($keluar);
    }

    /**
     * Check if booking is active (in progress)
     */
    public function getIsActiveAttribute()
    {
        return $this->status === 'in_progress';
    }

    /**
     * Check if booking is completed
     */
    public function getIsCompletedAttribute()
    {
        return $this->status === 'selesai';
    }

    /**
     * Check if booking is pending
     */
    public function getIsPendingAttribute()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if DP has been paid
     */
    public function getDpPaidAttribute()
    {
        return $this->dp_dibayar === 'Ya';
    }

    /**
     * Check if booking has extension request
     */
    public function getHasExtensionRequestAttribute()
    {
        return $this->status === 'perpanjangan';
    }

    /**
     * Check if booking is cancelled
     */
    public function getIsCancelledAttribute()
    {
        return $this->status === 'pembatalan';
    }

    /**
     * Get date range for the booking
     */
    public function getDateRangeAttribute()
    {
        $dates = [];
        $start = Carbon::parse($this->tanggal_masuk);
        $end = Carbon::parse($this->tanggal_keluar);

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dates[] = $date->toDateString();
        }

        return $dates;
    }

    /**
     * Get progress percentage of daily logs
     */
    public function getProgressPercentageAttribute()
    {
        $totalDays = $this->durasi;
        $filledDays = $this->dailyLogs()->count();

        if ($totalDays <= 0) return 0;

        return ($filledDays / $totalDays) * 100;
    }
}
