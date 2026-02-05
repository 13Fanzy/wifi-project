<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Simpan pembayaran baru dengan auto-detect status
     * - Bulan lalu: Otomatis 'Terlambat'
     * - Bulan ini/depan: Berdasarkan tanggal bayar (< 5 = Lebih Awal, 5-7 = Tepat Waktu, > 7 = Terlambat)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'bulan_tagihan' => 'required|date_format:Y-m',
            'jumlah_bayar' => 'required|numeric|min:0',
            'catatan' => 'required|string|max:500',
        ]);

        $now = Carbon::now();
        $currentMonth = $now->format('Y-m');
        $bulanTagihan = $validated['bulan_tagihan'];

        // Cek apakah pelanggan sudah bergabung pada bulan tagihan
        $customer = Customer::find($validated['customer_id']);
        $tanggalBergabung = Carbon::parse($customer->tanggal_bergabung)->format('Y-m');

        if ($bulanTagihan < $tanggalBergabung) {
            return back()->with('error', "Tidak bisa bayar untuk bulan sebelum pelanggan bergabung ({$customer->tanggal_bergabung->format('F Y')})");
        }

        // Cek apakah sudah ada pembayaran untuk bulan ini
        $existingPayment = Payment::where('customer_id', $validated['customer_id'])
            ->where('bulan_tagihan', $bulanTagihan)
            ->first();

        if ($existingPayment) {
            return back()->with('error', 'Pembayaran untuk bulan ini sudah ada!');
        }

        // Tentukan status pembayaran
        if ($bulanTagihan < $currentMonth) {
            // Bulan lalu: Otomatis Terlambat
            $status = 'Terlambat';
        } else {
            // Bulan ini atau ke depan: Berdasarkan tanggal bayar
            $status = Payment::determinePaymentStatus($now);
        }

        Payment::create([
            'customer_id' => $validated['customer_id'],
            'bulan_tagihan' => $bulanTagihan,
            'tanggal_bayar' => $now,
            'jumlah_bayar' => $validated['jumlah_bayar'],
            'status_pembayaran' => $status,
            'catatan' => $validated['catatan'],
        ]);

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', "Pembayaran berhasil dikonfirmasi dengan status: {$status}");
    }

    /**
     * Batalkan/Hapus pembayaran
     */
    public function destroy(Payment $payment)
    {
        $customerName = $payment->customer->nama;
        $bulanTagihan = $payment->formatted_bulan_tagihan;

        $payment->delete();

        return back()->with('success', "Pembayaran {$customerName} untuk {$bulanTagihan} berhasil dibatalkan.");
    }
}
