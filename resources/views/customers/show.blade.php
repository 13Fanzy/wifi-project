@extends('layouts.app')

@section('title', $customer->nama)
@section('header', 'Detail Pelanggan')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb -->
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('customers.index') }}" class="text-sm text-gray-500 hover:text-indigo-600">Pelanggan</a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-700">{{ $customer->nama }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Customer Info Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 bg-gradient-to-r from-indigo-500 to-purple-600">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-16 w-16 rounded-full bg-white/20 flex items-center justify-center">
                                <span class="text-2xl font-bold text-white">{{ strtoupper(substr($customer->nama, 0, 1)) }}</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-xl font-bold text-white">{{ $customer->nama }}</h2>
                            <p class="text-indigo-100">
                                @if($customer->status_aktif)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">Nonaktif</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-5 space-y-4">
                    <!-- Nomor WA -->
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-gray-400 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                        </svg>
                        <div class="ml-3">
                            <p class="text-xs text-gray-500">WhatsApp</p>
                            <p class="text-sm font-medium text-gray-900">{{ $customer->nomor_wa ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <div class="ml-3">
                            <p class="text-xs text-gray-500">Alamat</p>
                            <p class="text-sm text-gray-900">{{ $customer->alamat ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Paket -->
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="ml-3">
                            <p class="text-xs text-gray-500">Paket Bulanan</p>
                            <p class="text-lg font-bold text-indigo-600">Rp {{ number_format($customer->paket_harga, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Tanggal Bergabung -->
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <div class="ml-3">
                            <p class="text-xs text-gray-500">Bergabung Sejak</p>
                            <p class="text-sm text-gray-900">{{ $customer->tanggal_bergabung->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <a href="{{ route('customers.edit', $customer) }}"
                        class="w-full inline-flex justify-center items-center px-4 py-2.5 text-sm font-medium text-indigo-700 bg-indigo-100 rounded-lg hover:bg-indigo-200 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Data Pelanggan
                    </a>
                </div>
            </div>
        </div>

        <!-- Payment History Section -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Riwayat Pembayaran</h3>
                        <p class="text-sm text-gray-500">Daftar pembayaran pelanggan ini</p>
                    </div>
                    <a href="{{ route('reports.index') }}"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-indigo-700 bg-indigo-100 rounded-lg hover:bg-indigo-200 transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Input Pembayaran
                    </a>
                </div>

                @if($payments->count() > 0)
                <!-- Desktop Table (hidden on mobile) -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Bulan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Bayar</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($payments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::createFromFormat('Y-m', $payment->bulan_tagihan)->translatedFormat('F Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $payment->tanggal_bayar->format('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                    {{ $payment->catatan ?? '-' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card Layout (shown only on mobile) -->
                <div class="md:hidden divide-y divide-gray-200">
                    @foreach($payments as $payment)
                    <div class="p-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::createFromFormat('Y-m', $payment->bulan_tagihan)->translatedFormat('F Y') }}
                                </p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    {{ $payment->tanggal_bayar->format('d M Y, H:i') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-indigo-600">
                                    Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}
                                </p>
                                @php
                                $badgeColor = match($payment->status_pembayaran) {
                                'Lebih Awal' => 'bg-green-100 text-green-800',
                                'Tepat Waktu' => 'bg-blue-100 text-blue-800',
                                'Terlambat' => 'bg-orange-100 text-orange-800',
                                default => 'bg-gray-100 text-gray-800',
                                };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $badgeColor }} mt-1">
                                    {{ $payment->status_pembayaran }}
                                </span>
                            </div>
                        </div>
                        @if($payment->catatan)
                        <p class="mt-2 text-xs text-gray-500 truncate">{{ $payment->catatan }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="px-4 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="mt-4 text-sm text-gray-500">Belum ada riwayat pembayaran</p>
                    <a href="{{ route('reports.index') }}" class="mt-2 inline-block text-sm text-indigo-600 hover:underline">
                        Input pembayaran di halaman laporan →
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection