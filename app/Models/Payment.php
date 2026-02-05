<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'bulan_tagihan',
        'tanggal_bayar',
        'jumlah_bayar',
        'status_pembayaran',
        'catatan',
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
        'jumlah_bayar' => 'integer',
    ];

    /**
     * Relasi ke pelanggan
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Tentukan status pembayaran berdasarkan tanggal bayar
     * - Sebelum tanggal 5: Lebih Awal (Hijau)
     * - Tanggal 5-7: Tepat Waktu (Biru)
     * - Setelah tanggal 7: Terlambat (Oranye)
     */
    public static function determinePaymentStatus(Carbon $tanggalBayar): string
    {
        $day = $tanggalBayar->day;

        if ($day < 5) {
            return 'Lebih Awal';
        } elseif ($day <= 7) {
            return 'Tepat Waktu';
        } else {
            return 'Terlambat';
        }
    }

    /**
     * Mendapatkan warna badge berdasarkan status
     */
    public function getBadgeColorAttribute(): string
    {
        return match ($this->status_pembayaran) {
            'Lebih Awal' => 'green',
            'Tepat Waktu' => 'blue',
            'Terlambat' => 'orange',
            default => 'gray',
        };
    }

    /**
     * Format bulan tagihan menjadi readable
     */
    public function getFormattedBulanTagihanAttribute(): string
    {
        return Carbon::createFromFormat('Y-m', $this->bulan_tagihan)->translatedFormat('F Y');
    }
}
