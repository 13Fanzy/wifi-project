@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Pelanggan Aktif -->
        <div class="bg-white overflow-hidden rounded-xl shadow-sm hover:shadow-md transition-shadow">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 bg-indigo-100 rounded-lg">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pelanggan Aktif</dt>
                            <dd class="text-3xl font-bold text-gray-900">{{ $totalPelangganAktif }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-indigo-50 px-5 py-3">
                <a href="{{ route('customers.index') }}" class="text-sm text-indigo-700 hover:text-indigo-900 font-medium">Lihat semua →</a>
            </div>
        </div>

        <!-- Sudah Bayar -->
        <div class="bg-white overflow-hidden rounded-xl shadow-sm hover:shadow-md transition-shadow">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Sudah Bayar</dt>
                            <dd class="text-3xl font-bold text-green-600">{{ $sudahBayar }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-green-50 px-5 py-3">
                <a href="{{ route('customers.index', ['filter' => 'sudah_bayar']) }}" class="text-sm text-green-700 hover:text-green-900 font-medium">Filter sudah bayar →</a>
            </div>
        </div>

        <!-- Belum Bayar -->
        <div class="bg-white overflow-hidden rounded-xl shadow-sm hover:shadow-md transition-shadow">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 bg-amber-100 rounded-lg">
                            <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Belum Bayar</dt>
                            <dd class="text-3xl font-bold text-amber-600">{{ $belumBayar }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-amber-50 px-5 py-3">
                <a href="{{ route('customers.index', ['filter' => 'belum_bayar']) }}" class="text-sm text-amber-700 hover:text-amber-900 font-medium">Filter belum bayar →</a>
            </div>
        </div>

        <!-- Total Pendapatan -->
        <div class="bg-white overflow-hidden rounded-xl shadow-sm hover:shadow-md transition-shadow">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 bg-emerald-100 rounded-lg">
                            <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pendapatan Bulan Ini</dt>
                            <dd class="text-2xl font-bold text-emerald-600">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-emerald-50 px-5 py-3">
                <span class="text-sm text-emerald-700 font-medium">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Two column layout -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Overdue Customers -->
        @if($pelangganOverdue->count() > 0)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 bg-red-50">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-red-800">Pelanggan Menunggak</h3>
                </div>
                <p class="text-sm text-red-600 mt-1">Belum bayar setelah tanggal 7</p>
            </div>
            <ul class="divide-y divide-gray-200">
                @foreach($pelangganOverdue as $customer)
                <li class="px-5 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $customer->nama }}</p>
                            <p class="text-sm text-gray-500">{{ $customer->nomor_wa ?? 'Tidak ada nomor' }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Menunggak
                            </span>
                            <a href="{{ route('customers.show', $customer) }}"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg border border-indigo-300 text-indigo-600 hover:bg-indigo-50 transition-colors">
                                Bayar
                            </a>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
            @if($pelangganOverdue->count() >= 10)
            <div class="px-5 py-3 bg-gray-50 border-t">
                <a href="{{ route('customers.index', ['filter' => 'overdue']) }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Lihat semua menunggak →</a>
            </div>
            @endif
        </div>
        @endif

        <!-- Recent Payments -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden {{ $pelangganOverdue->count() === 0 ? 'lg:col-span-2' : '' }}">
            <div class="px-5 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Pembayaran Terbaru</h3>
            </div>
            @if($pembayaranTerbaru->count() > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($pembayaranTerbaru as $payment)
                <li class="px-5 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $payment->customer->nama }}</p>
                            <p class="text-sm text-gray-500">{{ $payment->tanggal_bayar->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</span>
                            @php
                            $badgeColor = match($payment->status_pembayaran) {
                            'Lebih Awal' => 'bg-green-100 text-green-800',
                            'Tepat Waktu' => 'bg-blue-100 text-blue-800',
                            'Terlambat' => 'bg-orange-100 text-orange-800',
                            default => 'bg-gray-100 text-gray-800',
                            };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeColor }}">
                                {{ $payment->status_pembayaran }}
                            </span>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
            @else
            <div class="px-5 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="mt-2 text-sm text-gray-500">Belum ada pembayaran bulan ini</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Quick action for adding customer -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-8 sm:px-10 sm:py-10">
            <div class="flex flex-col sm:flex-row items-center justify-between">
                <div class="text-center sm:text-left mb-4 sm:mb-0">
                    <h3 class="text-xl font-bold text-white">Tambah Pelanggan Baru</h3>
                    <p class="mt-1 text-indigo-100">Daftarkan pelanggan baru ke sistem</p>
                </div>
                <a href="{{ route('customers.create') }}"
                    class="inline-flex items-center px-5 py-3 rounded-lg bg-white text-indigo-600 font-semibold hover:bg-indigo-50 transition-colors shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Pelanggan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection