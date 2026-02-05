<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Tampilkan daftar pelanggan dengan filter
     */
    public function index(Request $request)
    {
        $bulanIni = Carbon::now()->format('Y-m');
        $search = $request->input('search');
        $filter = $request->input('filter'); // sudah_bayar, belum_bayar, overdue

        $query = Customer::query();

        // Search by nama atau nomor_wa
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nomor_wa', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        // Filter by status pembayaran
        $customerIdsSudahBayar = Payment::where('bulan_tagihan', $bulanIni)
            ->pluck('customer_id')
            ->toArray();

        if ($filter === 'sudah_bayar') {
            $query->whereIn('id', $customerIdsSudahBayar);
        } elseif ($filter === 'belum_bayar') {
            $query->whereNotIn('id', $customerIdsSudahBayar);
        } elseif ($filter === 'overdue') {
            $today = Carbon::now()->day;
            if ($today > 7) {
                $query->where('status_aktif', true)
                    ->whereNotIn('id', $customerIdsSudahBayar);
            } else {
                // Tidak ada overdue sebelum tanggal 7
                $query->whereRaw('1 = 0');
            }
        }

        // Pagination: default 15, max 100
        $perPage = min((int) $request->input('per_page', 15), 100);
        $customers = $query->orderBy('nama')->paginate($perPage);

        // Append payment status ke setiap customer
        $customers->getCollection()->transform(function ($customer) use ($bulanIni, $customerIdsSudahBayar) {
            $customer->sudah_bayar_bulan_ini = in_array($customer->id, $customerIdsSudahBayar);
            $payment = $customer->getPaymentForMonth($bulanIni);
            $customer->payment_bulan_ini = $payment;
            return $customer;
        });

        return view('customers.index', compact('customers', 'search', 'filter', 'bulanIni', 'perPage'));
    }

    /**
     * Tampilkan form tambah pelanggan
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Simpan pelanggan baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_wa' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'paket_harga' => 'required|numeric|min:0',
            'tanggal_bergabung' => 'required|date',
            'status_aktif' => 'boolean',
        ]);

        $validated['status_aktif'] = $request->has('status_aktif');

        Customer::create($validated);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Pelanggan berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail pelanggan dengan riwayat pembayaran
     */
    public function show(Customer $customer)
    {
        $bulanIni = Carbon::now()->format('Y-m');

        // Riwayat pembayaran
        $payments = $customer->payments()
            ->orderBy('bulan_tagihan', 'desc')
            ->get();

        // Cek apakah sudah bayar bulan ini
        $sudahBayarBulanIni = $customer->hasPaidForMonth($bulanIni);

        return view('customers.show', compact('customer', 'payments', 'bulanIni', 'sudahBayarBulanIni'));
    }

    /**
     * Tampilkan form edit pelanggan
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update data pelanggan
     * Jika paket_harga berubah, otomatis update pembayaran bulan ini dan ke depan
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_wa' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'paket_harga' => 'required|numeric|min:0',
            'tanggal_bergabung' => 'required|date',
            'status_aktif' => 'boolean',
        ]);

        $validated['status_aktif'] = $request->has('status_aktif');

        // Cek apakah paket_harga berubah
        $oldPaketHarga = $customer->paket_harga;
        $newPaketHarga = (int) $validated['paket_harga'];
        $paketChanged = $oldPaketHarga != $newPaketHarga;

        $customer->update($validated);

        // Jika paket berubah, update pembayaran bulan ini dan ke depan
        $updatedPayments = 0;
        if ($paketChanged) {
            $bulanIni = Carbon::now()->format('Y-m');

            // Update semua pembayaran >= bulan ini
            $updatedPayments = Payment::where('customer_id', $customer->id)
                ->where('bulan_tagihan', '>=', $bulanIni)
                ->update(['jumlah_bayar' => $newPaketHarga]);
        }

        $message = 'Data pelanggan berhasil diperbarui!';
        if ($updatedPayments > 0) {
            $message .= " {$updatedPayments} pembayaran telah disesuaikan dengan paket baru.";
        }

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', $message);
    }

    /**
     * Hapus pelanggan
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('success', 'Pelanggan berhasil dihapus!');
    }
}
