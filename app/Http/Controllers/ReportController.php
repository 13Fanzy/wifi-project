<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Tampilkan laporan pembayaran bulanan
     */
    public function index(Request $request)
    {
        // Get month and year from request, default to current
        $bulanAngka = $request->get('bulan_angka', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);

        // Combine to format Y-m
        $bulan = sprintf('%04d-%02d', $tahun, $bulanAngka);
        $currentMonth = Carbon::now()->format('Y-m');
        $isCurrentMonth = ($bulan === $currentMonth);

        $filter = $request->get('status'); // sudah_bayar, belum_bayar, atau null untuk semua

        // Get customer IDs yang sudah bayar bulan ini (termasuk yang sudah tidak aktif)
        $customerIdsSudahBayar = Payment::where('bulan_tagihan', $bulan)
            ->pluck('customer_id')
            ->toArray();

        // Build query based on context
        $isFutureMonth = ($bulan > $currentMonth);

        if ($isCurrentMonth || $isFutureMonth) {
            // Bulan berjalan atau bulan depan: Untuk pembayaran normal atau pembayaran di muka
            if ($filter === 'sudah_bayar') {
                // Hanya yang sudah bayar (termasuk yang tidak aktif - untuk tracking)
                $query = Customer::whereIn('id', $customerIdsSudahBayar);
            } elseif ($filter === 'belum_bayar') {
                // Hanya pelanggan aktif yang belum bayar
                $query = Customer::where('status_aktif', true)
                    ->whereNotIn('id', $customerIdsSudahBayar);
            } else {
                // Semua: Pelanggan aktif + pelanggan tidak aktif yang sudah bayar
                $query = Customer::where(function ($q) use ($customerIdsSudahBayar) {
                    $q->where('status_aktif', true)
                        ->orWhereIn('id', $customerIdsSudahBayar);
                });
            }
        } else {
            // Bulan sebelumnya (history): 
            // - Hanya tampilkan pelanggan yang sudah bergabung pada bulan tersebut
            // - Pelanggan bisa bayar untuk bulan lalu dengan status Terlambat
            $bulanAkhir = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth()->format('Y-m-d');

            if ($filter === 'sudah_bayar') {
                // Semua yang sudah bayar bulan itu (termasuk yang tidak aktif)
                $query = Customer::whereIn('id', $customerIdsSudahBayar);
            } elseif ($filter === 'belum_bayar') {
                // Pelanggan aktif yang belum bayar DAN sudah bergabung pada bulan tersebut
                $query = Customer::where('status_aktif', true)
                    ->whereNotIn('id', $customerIdsSudahBayar)
                    ->whereDate('tanggal_bergabung', '<=', $bulanAkhir);
            } else {
                // Semua: Pelanggan yang sudah bayar + pelanggan aktif yang sudah bergabung
                $query = Customer::where(function ($q) use ($customerIdsSudahBayar, $bulanAkhir) {
                    $q->where(function ($q2) use ($bulanAkhir) {
                        $q2->where('status_aktif', true)
                            ->whereDate('tanggal_bergabung', '<=', $bulanAkhir);
                    })->orWhereIn('id', $customerIdsSudahBayar);
                });
            }
        }

        // Pagination: default 15, max 100
        $perPage = min((int) $request->input('per_page', 15), 100);
        $customers = $query->orderBy('nama')->paginate($perPage);

        // Get ALL customers for search (without pagination limits)
        $allCustomersForSearch = (clone $query)->orderBy('nama')->get()->map(function ($customer) use ($bulan, $customerIdsSudahBayar, $currentMonth) {
            $bulanBergabung = Carbon::parse($customer->tanggal_bergabung)->format('Y-m');
            $isPastMonth = $bulan < $currentMonth;
            $payment = $customer->getPaymentForMonth($bulan);

            return [
                'id' => $customer->id,
                'nama' => $customer->nama,
                'alamat' => $customer->alamat,
                'nomor_wa' => $customer->nomor_wa,
                'paket_harga' => $customer->paket_harga,
                'status_aktif' => $customer->status_aktif,
                'tanggal_bergabung' => $customer->tanggal_bergabung,
                'sudah_bayar' => in_array($customer->id, $customerIdsSudahBayar),
                'payment' => $payment ? [
                    'id' => $payment->id,
                    'jumlah_bayar' => $payment->jumlah_bayar,
                    'status_pembayaran' => $payment->status_pembayaran,
                    'tanggal_bayar' => $payment->tanggal_bayar->format('d M Y, H:i'),
                ] : null,
                'can_pay' => !in_array($customer->id, $customerIdsSudahBayar) && $customer->status_aktif && $bulan >= $bulanBergabung,
                'is_past_month' => $isPastMonth,
            ];
        });

        // Append payment info ke setiap customer (for paginated view)
        $customers->getCollection()->transform(function ($customer) use ($bulan, $customerIdsSudahBayar) {
            $customer->sudah_bayar = in_array($customer->id, $customerIdsSudahBayar);
            $customer->payment = $customer->getPaymentForMonth($bulan);
            return $customer;
        });

        // Statistics
        $totalAktif = Customer::where('status_aktif', true)->count();
        $sudahBayar = count($customerIdsSudahBayar);
        $belumBayar = $totalAktif - $sudahBayar;
        $totalPendapatan = (int) Payment::where('bulan_tagihan', $bulan)->sum('jumlah_bayar');

        // Available years (from earliest payment to current year + 5 for advance payments)
        $earliestYear = Payment::min('bulan_tagihan');
        $startYear = $earliestYear ? (int) substr($earliestYear, 0, 4) : Carbon::now()->year;
        $availableYears = range(Carbon::now()->year + 5, $startYear);

        // Month names in Indonesian
        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        // Check if current filter month is overdue period (after 7th)
        $isOverduePeriod = false;
        if ($isCurrentMonth && Carbon::now()->day > 7) {
            $isOverduePeriod = true;
        }

        return view('reports.index', compact(
            'customers',
            'allCustomersForSearch',
            'bulan',
            'bulanAngka',
            'tahun',
            'namaBulan',
            'availableYears',
            'filter',
            'totalAktif',
            'sudahBayar',
            'belumBayar',
            'totalPendapatan',
            'isOverduePeriod',
            'isCurrentMonth',
            'perPage'
        ));
    }

    /**
     * Export laporan ke PDF
     * Menggunakan DomPDF dengan optimasi untuk hosting gratis
     */
    public function export(Request $request)
    {
        // Get month and year from request
        $bulanAngka = $request->get('bulan_angka', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);
        $bulan = sprintf('%04d-%02d', $tahun, $bulanAngka);
        $currentMonth = Carbon::now()->format('Y-m');
        $isCurrentMonth = ($bulan === $currentMonth);
        $filter = $request->get('status');

        // Get customer IDs yang sudah bayar
        $customerIdsSudahBayar = Payment::where('bulan_tagihan', $bulan)
            ->pluck('customer_id')
            ->toArray();

        // Build query
        $isFutureMonth = ($bulan > $currentMonth);

        if ($isCurrentMonth || $isFutureMonth) {
            if ($filter === 'sudah_bayar') {
                $query = Customer::whereIn('id', $customerIdsSudahBayar);
            } elseif ($filter === 'belum_bayar') {
                $query = Customer::where('status_aktif', true)
                    ->whereNotIn('id', $customerIdsSudahBayar);
            } else {
                $query = Customer::where(function ($q) use ($customerIdsSudahBayar) {
                    $q->where('status_aktif', true)
                        ->orWhereIn('id', $customerIdsSudahBayar);
                });
            }
        } else {
            if ($filter === 'sudah_bayar') {
                $query = Customer::whereIn('id', $customerIdsSudahBayar);
            } elseif ($filter === 'belum_bayar') {
                $query = Customer::where('status_aktif', true)
                    ->whereNotIn('id', $customerIdsSudahBayar);
            } else {
                $query = Customer::where(function ($q) use ($customerIdsSudahBayar) {
                    $q->where('status_aktif', true)
                        ->orWhereIn('id', $customerIdsSudahBayar);
                });
            }
        }

        // Limit 200 untuk PDF agar tidak terlalu berat
        $customers = $query->orderBy('nama')->limit(200)->get();

        // Prepare data untuk view
        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $isOverduePeriod = $isCurrentMonth && Carbon::now()->day > 7;

        // Transform customers data
        $customers->transform(function ($customer) use ($bulan, $customerIdsSudahBayar, $isOverduePeriod) {
            $sudahBayar = in_array($customer->id, $customerIdsSudahBayar);
            $payment = $customer->getPaymentForMonth($bulan);

            $customer->sudah_bayar = $sudahBayar;
            $customer->payment = $payment;
            $customer->is_overdue = $isOverduePeriod;

            return $customer;
        });

        $data = [
            'customers' => $customers,
            'bulanAngka' => $bulanAngka,
            'tahun' => $tahun,
            'namaBulan' => $namaBulan,
            'filter' => $filter,
            'isOverduePeriod' => $isOverduePeriod,
            'exportDate' => Carbon::now()->format('d/m/Y H:i:s'),
        ];

        $pdf = Pdf::loadView('reports.pdf', $data)
            ->setPaper('a4', 'landscape');

        $filename = sprintf('Laporan_WiFi_%s_%d.pdf', $namaBulan[(int)$bulanAngka], $tahun);

        return $pdf->download($filename);
    }
}
