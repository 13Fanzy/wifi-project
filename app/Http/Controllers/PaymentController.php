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

        // Auto-detect status berdasarkan tanggal bayar
        $status = Payment::determinePaymentStatus($now);

        // Cek apakah sudah ada pembayaran untuk bulan ini
        $existingPayment = Payment::where('customer_id', $validated['customer_id'])
            ->where('bulan_tagihan', $validated['bulan_tagihan'])
            ->first();

        if ($existingPayment) {
            return back()->with('error', 'Pembayaran untuk bulan ini sudah ada!');
        }

        Payment::create([
            'customer_id' => $validated['customer_id'],
            'bulan_tagihan' => $validated['bulan_tagihan'],
            'tanggal_bayar' => $now,
            'jumlah_bayar' => $validated['jumlah_bayar'],
            'status_pembayaran' => $status,
            'catatan' => $validated['catatan'],
        ]);

        $customer = Customer::find($validated['customer_id']);

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', "Pembayaran berhasil dikonfirmasi dengan status: {$status}");
    }
}
