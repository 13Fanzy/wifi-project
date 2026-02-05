<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Customers CRUD
    Route::resource('customers', CustomerController::class);

    // Payments
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');

    // Reports
    Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/laporan/export', [ReportController::class, 'export'])->name('reports.export');

    // Profile - Update password sendiri
    Route::get('/profile/password', [UserController::class, 'showChangePassword'])->name('profile.password');
    Route::put('/profile/password', [UserController::class, 'changePassword'])->name('profile.password.update');

    // User Management (superadmin only)
    Route::middleware('superadmin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::put('/users/{user}/password', [UserController::class, 'updatePassword'])->name('users.password.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});

Route::get('/debug-payments', function () {
    $bulanIni = \Carbon\Carbon::now()->format('Y-m');

    $payments = \App\Models\Payment::where('bulan_tagihan', $bulanIni)
        ->with('customer:id,nama,paket_harga,status_aktif')
        ->orderBy('created_at', 'desc')
        ->get();

    $totalSum = $payments->sum('jumlah_bayar');
    $count = $payments->count();

    return response()->json([
        'bulan' => $bulanIni,
        'total_pembayaran' => $count,
        'total_pendapatan' => $totalSum,
        'formatted' => 'Rp ' . number_format($totalSum, 0, ',', '.'),
        'detail_pembayaran' => $payments->map(function ($p) {
            return [
                'id' => $p->id,
                'customer_id' => $p->customer_id,
                'customer_nama' => $p->customer->nama ?? 'DELETED',
                'customer_aktif' => $p->customer->status_aktif ?? false,
                'customer_paket' => $p->customer->paket_harga ?? 0,
                'jumlah_bayar' => $p->jumlah_bayar,
                'bulan_tagihan' => $p->bulan_tagihan,
                'tanggal_bayar' => $p->tanggal_bayar->format('Y-m-d H:i:s'),
                'status' => $p->status_pembayaran,
            ];
        })
    ]);
})->middleware('auth');