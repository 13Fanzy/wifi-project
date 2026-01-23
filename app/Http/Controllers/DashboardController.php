<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard dengan statistik
     */
    public function index()
    {
        $bulanIni = Carbon::now()->format('Y-m');
        $today = Carbon::now()->day;

        // Total pelanggan aktif
        $totalPelangganAktif = Customer::where('status_aktif', true)->count();

        // Customer IDs yang sudah bayar bulan ini
        $customerIdsSudahBayar = Payment::where('bulan_tagihan', $bulanIni)
            ->pluck('customer_id')
            ->toArray();

        // Sudah bayar bulan ini
        $sudahBayar = Customer::where('status_aktif', true)
            ->whereIn('id', $customerIdsSudahBayar)
            ->count();

        // Belum bayar bulan ini
        $belumBayar = Customer::where('status_aktif', true)
            ->whereNotIn('id', $customerIdsSudahBayar)
            ->count();

        // Total pendapatan bulan ini
        $totalPendapatan = Payment::where('bulan_tagihan', $bulanIni)
            ->sum('jumlah_bayar');

        // Pelanggan overdue (belum bayar dan sudah lewat tanggal 7)
        $pelangganOverdue = collect();
        if ($today > 7) {
            $pelangganOverdue = Customer::where('status_aktif', true)
                ->whereNotIn('id', $customerIdsSudahBayar)
                ->orderBy('nama')
                ->take(10)
                ->get();
        }

        // Pembayaran terbaru
        $pembayaranTerbaru = Payment::with('customer')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalPelangganAktif',
            'sudahBayar',
            'belumBayar',
            'totalPendapatan',
            'pelangganOverdue',
            'pembayaranTerbaru',
            'bulanIni'
        ));
    }
}
