<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nomor_wa',
        'alamat',
        'paket_harga',
        'tanggal_bergabung',
        'status_aktif',
    ];

    protected $casts = [
        'paket_harga' => 'integer',
        'tanggal_bergabung' => 'date',
        'status_aktif' => 'boolean',
    ];

    /**
     * Relasi ke pembayaran
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Cek apakah sudah bayar bulan tertentu
     */
    public function hasPaidForMonth(string $bulanTagihan): bool
    {
        return $this->payments()->where('bulan_tagihan', $bulanTagihan)->exists();
    }

    /**
     * Ambil pembayaran untuk bulan tertentu
     */
    public function getPaymentForMonth(string $bulanTagihan): ?Payment
    {
        return $this->payments()->where('bulan_tagihan', $bulanTagihan)->first();
    }

    /**
     * Cek apakah pelanggan ini overdue untuk bulan berjalan
     */
    public function isOverdue(?string $bulanTagihan = null): bool
    {
        $bulanTagihan = $bulanTagihan ?? Carbon::now()->format('Y-m');
        $today = Carbon::now()->day;

        // Overdue jika setelah tanggal 7 dan belum bayar
        return $today > 7 && !$this->hasPaidForMonth($bulanTagihan);
    }

    /**
     * Ambil status pembayaran bulan ini
     */
    public function getPaymentStatusAttribute(): string
    {
        $bulanIni = Carbon::now()->format('Y-m');
        $payment = $this->getPaymentForMonth($bulanIni);

        if ($payment) {
            return $payment->status_pembayaran;
        }

        if ($this->isOverdue()) {
            return 'Overdue';
        }

        return 'Belum Bayar';
    }
}
