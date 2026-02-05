@extends('layouts.app')

@section('title', 'Laporan Pembayaran')
@section('header', 'Laporan Pembayaran Bulanan')

@section('content')
<div x-data="{ 
    showPaymentModal: false, 
    selectedCustomer: null,
    searchQuery: '',
    filterBySearch(nama) {
        if (!this.searchQuery.trim()) return true;
        return nama.toLowerCase().includes(this.searchQuery.toLowerCase());
    }
}" class="space-y-6">
    <!-- Back Button -->
    <div>
        <button type="button" onclick="history.back()"
            class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-white rounded-lg border border-gray-300 hover:bg-gray-50 hover:text-gray-800 transition-colors shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </button>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form method="GET" action="{{ route('reports.index') }}" class="space-y-3">
            <!-- Row 1: Bulan & Tahun -->
            <div class="grid grid-cols-2 gap-3">
                <!-- Month Filter -->
                <div>
                    <label for="bulan_angka" class="block text-xs font-medium text-gray-700 mb-1">Bulan</label>
                    <select name="bulan_angka" id="bulan_angka"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @foreach($namaBulan as $num => $nama)
                        <option value="{{ $num }}" {{ (int)$bulanAngka === $num ? 'selected' : '' }}>
                            {{ $nama }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Year Filter -->
                <div>
                    <label for="tahun" class="block text-xs font-medium text-gray-700 mb-1">Tahun</label>
                    <select name="tahun" id="tahun"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ (int)$tahun === $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Row 2: Status, Per Page & Button -->
            <div class="flex gap-3">
                <!-- Status Filter -->
                <div class="flex-1">
                    <label for="status" class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="" {{ $filter === null ? 'selected' : '' }}>Semua</option>
                        <option value="sudah_bayar" {{ $filter === 'sudah_bayar' ? 'selected' : '' }}>✅ Sudah Bayar</option>
                        <option value="belum_bayar" {{ $filter === 'belum_bayar' ? 'selected' : '' }}>⏳ Belum Bayar</option>
                    </select>
                </div>

                <!-- Per Page -->
                <div>
                    <label for="per_page" class="block text-xs font-medium text-gray-700 mb-1">Per Hal</label>
                    <select name="per_page" id="per_page"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="flex items-end">
                    <button type="submit"
                        class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                        <svg class="w-4 h-4 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span class="hidden sm:inline">Filter</span>
                    </button>
                </div>

                <!-- Export PDF Button - di sebelah tombol Filter -->
                <div class="flex items-end">
                    <a href="{{ route('reports.export', ['bulan_angka' => $bulanAngka, 'tahun' => $tahun, 'status' => $filter]) }}"
                        style="display: inline-flex; align-items: center; padding: 8px 16px; background-color: #10B981; color: white; font-size: 14px; font-weight: 500; border-radius: 8px; text-decoration: none;">
                        📄 PDF
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Search Input Section -->
    <div class="bg-white rounded-xl shadow-sm p-5">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text"
                x-model="searchQuery"
                placeholder="Cari pelanggan berdasarkan nama..."
                class="block w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow">
            <button type="button"
                x-show="searchQuery.length > 0"
                @click="searchQuery = ''"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Pelanggan -->
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center">
                <div class="p-2 bg-indigo-100 rounded-lg">
                    <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-xs text-gray-500">Total Aktif</p>
                    <p class="text-xl font-bold text-gray-900">{{ $totalAktif }}</p>
                </div>
            </div>
        </div>

        <!-- Sudah Bayar -->
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-xs text-gray-500">Sudah Bayar</p>
                    <p class="text-xl font-bold text-green-600">{{ $sudahBayar }}</p>
                </div>
            </div>
        </div>

        <!-- Belum Bayar -->
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center">
                <div class="p-2 bg-amber-100 rounded-lg">
                    <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-xs text-gray-500">Belum Bayar</p>
                    <p class="text-xl font-bold text-amber-600">{{ $belumBayar }}</p>
                </div>
            </div>
        </div>

        <!-- Total Pendapatan -->
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center">
                <div class="p-2 bg-emerald-100 rounded-lg">
                    <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-xs text-gray-500">Pendapatan</p>
                    <p class="text-lg font-bold text-emerald-600">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-4 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-800">
                Daftar Pelanggan - {{ $namaBulan[(int)$bulanAngka] }} {{ $tahun }}
            </h3>
            <p class="text-sm text-gray-500">
                @if($filter === 'sudah_bayar')
                Menampilkan pelanggan yang sudah bayar
                @elseif($filter === 'belum_bayar')
                Menampilkan pelanggan yang belum bayar
                @else
                Menampilkan semua pelanggan aktif
                @endif
            </p>
        </div>

        <!-- Desktop Table (hidden on mobile) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pelanggan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nomor WA</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Bayar</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($customers as $index => $customer)
                    <tr x-show="filterBySearch('{{ addslashes($customer->nama) }}')" class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $customers->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium {{ !$customer->status_aktif ? 'text-gray-400 line-through' : 'text-gray-900' }}">{{ $customer->nama }}</span>
                                @if(!$customer->status_aktif)
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-200 text-gray-600">
                                    Tidak Aktif
                                </span>
                                @endif
                            </div>
                            <div class="text-sm text-gray-500 truncate max-w-xs">{{ $customer->alamat ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $customer->nomor_wa ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            @if($customer->sudah_bayar && $customer->payment)
                            Rp {{ number_format($customer->payment->jumlah_bayar, 0, ',', '.') }}
                            @if($customer->payment->jumlah_bayar != $customer->paket_harga)
                            <span class="text-xs text-gray-400">(paket: {{ number_format($customer->paket_harga, 0, ',', '.') }})</span>
                            @endif
                            @else
                            Rp {{ number_format($customer->paket_harga, 0, ',', '.') }}
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($customer->sudah_bayar && $customer->payment)
                            @php
                            $badgeColor = match($customer->payment->status_pembayaran) {
                            'Lebih Awal' => 'bg-green-100 text-green-800 border-green-200',
                            'Tepat Waktu' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'Terlambat' => 'bg-orange-100 text-orange-800 border-orange-200',
                            default => 'bg-gray-100 text-gray-800 border-gray-200',
                            };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $badgeColor }}">
                                {{ $customer->payment->status_pembayaran }}
                            </span>
                            @elseif($isOverduePeriod)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                Menunggak
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                Belum Bayar
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            @if($customer->sudah_bayar && $customer->payment)
                            {{ $customer->payment->tanggal_bayar->format('d M Y, H:i') }}
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            @php
                            $currentMonth = \Carbon\Carbon::now()->format('Y-m');
                            $canPay = !$customer->sudah_bayar && $customer->status_aktif && $bulan >= $currentMonth;
                            @endphp
                            @if($canPay)
                            <button type="button"
                                @click="showPaymentModal = true; selectedCustomer = { id: {{ $customer->id }}, nama: '{{ addslashes($customer->nama) }}', paket: {{ $customer->paket_harga }} }"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Bayar
                            </button>
                            @else
                            <a href="{{ route('customers.show', $customer) }}"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-indigo-700 bg-indigo-100 hover:bg-indigo-200 transition-colors">
                                Detail
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="mt-4 text-sm text-gray-500">Tidak ada data pelanggan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card Layout (shown only on mobile) -->
        <div class="md:hidden divide-y divide-gray-200">
            @forelse($customers as $index => $customer)
            <div x-show="filterBySearch('{{ addslashes($customer->nama) }}')" class="p-4 hover:bg-gray-50 transition-colors">
                <!-- Customer Name & Status -->
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-sm font-medium {{ !$customer->status_aktif ? 'text-gray-400 line-through' : 'text-gray-900' }}">{{ $customer->nama }}</span>
                            @if(!$customer->status_aktif)
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-200 text-gray-600">Tidak Aktif</span>
                            @endif
                        </div>
                    </div>
                    <!-- Payment Status Badge -->
                    <div class="ml-2 flex-shrink-0">
                        @if($customer->sudah_bayar && $customer->payment)
                        @php
                        $badgeColor = match($customer->payment->status_pembayaran) {
                        'Lebih Awal' => 'bg-green-100 text-green-800',
                        'Tepat Waktu' => 'bg-blue-100 text-blue-800',
                        'Terlambat' => 'bg-orange-100 text-orange-800',
                        default => 'bg-gray-100 text-gray-800',
                        };
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $badgeColor }}">
                            {{ $customer->payment->status_pembayaran }}
                        </span>
                        @elseif($isOverduePeriod)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Menunggak</span>
                        @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">Belum Bayar</span>
                        @endif
                    </div>
                </div>

                <!-- Customer Details -->
                <div class="mt-2 flex items-center gap-4 text-sm text-gray-600">
                    @if($customer->sudah_bayar && $customer->payment)
                    <span class="font-semibold text-indigo-600">Rp {{ number_format($customer->payment->jumlah_bayar, 0, ',', '.') }}</span>
                    @if($customer->payment->jumlah_bayar != $customer->paket_harga)
                    <span class="text-xs text-gray-400">(paket: {{ number_format($customer->paket_harga, 0, ',', '.') }})</span>
                    @endif
                    @else
                    <span class="font-semibold text-indigo-600">Rp {{ number_format($customer->paket_harga, 0, ',', '.') }}</span>
                    @endif
                    @if($customer->sudah_bayar && $customer->payment)
                    <span class="text-xs text-gray-500">{{ $customer->payment->tanggal_bayar->format('d M Y') }}</span>
                    @endif
                </div>

                <!-- Action Button -->
                <div class="mt-3">
                    @php
                    $currentMonth = \Carbon\Carbon::now()->format('Y-m');
                    $canPay = !$customer->sudah_bayar && $customer->status_aktif && $bulan >= $currentMonth;
                    @endphp
                    @if($canPay)
                    <button type="button"
                        @click="showPaymentModal = true; selectedCustomer = { id: {{ $customer->id }}, nama: '{{ addslashes($customer->nama) }}', paket: {{ $customer->paket_harga }} }"
                        class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Bayar Sekarang
                    </button>
                    @else
                    <a href="{{ route('customers.show', $customer) }}"
                        class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg text-indigo-700 bg-indigo-100 hover:bg-indigo-200 transition-colors">
                        Lihat Detail
                    </a>
                    @endif
                </div>
            </div>
            @empty
            <div class="p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="mt-4 text-sm text-gray-500">Tidak ada data pelanggan</p>
            </div>
            @endforelse
        </div>

        <!-- Summary Footer -->
        <div class="px-4 py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="text-sm text-gray-600">
                    Menampilkan {{ $customers->firstItem() ?? 0 }} - {{ $customers->lastItem() ?? 0 }} dari <span class="font-semibold">{{ $customers->total() }}</span> pelanggan
                </div>
                <div class="flex flex-wrap items-center gap-3 text-xs">
                    <div class="flex items-center">
                        <span class="w-2.5 h-2.5 rounded-full bg-green-500 mr-1"></span>
                        <span class="hidden sm:inline">Lebih Awal (&lt; Tgl 5)</span>
                        <span class="sm:hidden">Awal</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500 mr-1"></span>
                        <span class="hidden sm:inline">Tepat Waktu (Tgl 5-7)</span>
                        <span class="sm:hidden">Tepat</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-2.5 h-2.5 rounded-full bg-orange-500 mr-1"></span>
                        <span class="hidden sm:inline">Terlambat (&gt; Tgl 7)</span>
                        <span class="sm:hidden">Telat</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500 mr-1"></span>
                        Menunggak
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        @if($customers->hasPages())
        <div class="px-4 py-4 border-t border-gray-200 bg-white">
            {{ $customers->appends(['bulan_angka' => $bulanAngka, 'tahun' => $tahun, 'status' => $filter, 'per_page' => $perPage])->links() }}
        </div>
        @endif
    </div>

    <!-- Payment Modal -->
    <div x-show="showPaymentModal"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showPaymentModal = false"></div>

            <div x-show="showPaymentModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">

                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Pembayaran</h3>
                    <button @click="showPaymentModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form action="{{ route('payments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="customer_id" x-bind:value="selectedCustomer?.id">
                    <input type="hidden" name="bulan_tagihan" value="{{ $bulan }}">
                    <input type="hidden" name="jumlah_bayar" x-bind:value="selectedCustomer?.paket">

                    <div class="space-y-4">
                        <!-- Customer Info -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500">Pelanggan</p>
                            <p class="text-lg font-semibold text-gray-900" x-text="selectedCustomer?.nama"></p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm text-gray-500">Bulan Tagihan</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $namaBulan[(int)$bulanAngka] }} {{ $tahun }}</p>
                            </div>
                            <div class="bg-indigo-50 rounded-lg p-4">
                                <p class="text-sm text-indigo-600">Jumlah Bayar</p>
                                <p class="text-lg font-bold text-indigo-700">Rp <span x-text="selectedCustomer?.paket?.toLocaleString('id-ID')"></span></p>
                            </div>
                        </div>

                        <!-- Status Info -->
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                            <p class="text-xs text-amber-800">
                                <strong>Status otomatis:</strong>
                                @php $today = \Carbon\Carbon::now()->day; @endphp
                                @if($today < 5)
                                    <span class="text-green-700">Lebih Awal</span> (sebelum tanggal 5)
                                    @elseif($today <= 7)
                                        <span class="text-blue-700">Tepat Waktu</span> (tanggal 5-7)
                                        @else
                                        <span class="text-orange-700">Terlambat</span> (setelah tanggal 7)
                                        @endif
                            </p>
                        </div>

                        <!-- Catatan Bayar (Wajib) -->
                        <div>
                            <label for="catatan" class="block text-sm font-medium text-gray-700 mb-1">Catatan Bayar <span class="text-red-500">*</span></label>
                            <textarea name="catatan" id="catatan" rows="2" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="Contoh: Via transfer BCA, Tunai, dll..."></textarea>
                            <p class="mt-1 text-xs text-gray-500">Wajib diisi. Contoh: Via transfer, Tunai, dll.</p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="showPaymentModal = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-6 py-2 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors">
                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Konfirmasi Bayar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection