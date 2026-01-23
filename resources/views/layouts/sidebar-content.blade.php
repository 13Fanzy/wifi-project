<nav class="flex-1 px-2 py-4 space-y-1">
    <!-- Dashboard -->
    <a href="{{ route('dashboard') }}"
        class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors
              {{ request()->routeIs('dashboard') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-600' }}">
        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        Dashboard
    </a>

    <!-- Pelanggan -->
    <a href="{{ route('customers.index') }}"
        class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors
              {{ request()->routeIs('customers.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-600' }}">
        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        Pelanggan
    </a>

    <!-- Laporan Pembayaran -->
    <a href="{{ route('reports.index') }}"
        class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors
              {{ request()->routeIs('reports.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-600' }}">
        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        Laporan Bulanan
    </a>

    <!-- Divider -->
    <div class="border-t border-indigo-600 my-4"></div>

    <!-- User Management (Superadmin Only) -->
    @if(Auth::user()->isSuperAdmin())
    <a href="{{ route('users.index') }}"
        class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors
              {{ request()->routeIs('users.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-600' }}">
        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        Kelola User
    </a>
    @endif

    <!-- Profile / Ubah Password -->
    <a href="{{ route('profile.password') }}"
        class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors
              {{ request()->routeIs('profile.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-600' }}">
        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
        </svg>
        Ubah Password
    </a>

    <!-- Divider -->
    <div class="border-t border-indigo-600 my-4"></div>

    <!-- Info Bulan -->
    <div class="px-3 py-2 text-sm text-indigo-200">
        <div class="flex items-center">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
        </div>
    </div>

    <!-- Quick Info -->
    <div class="px-3 py-2 text-xs text-indigo-300">
        <p class="mb-1">Jatuh tempo: Tgl 5-7</p>
        <div class="space-y-1 mt-2">
            <div class="flex items-center">
                <span class="w-2 h-2 rounded-full bg-green-400 mr-2"></span>
                <span>Lebih Awal (&lt; Tgl 5)</span>
            </div>
            <div class="flex items-center">
                <span class="w-2 h-2 rounded-full bg-blue-400 mr-2"></span>
                <span>Tepat Waktu (Tgl 5-7)</span>
            </div>
            <div class="flex items-center">
                <span class="w-2 h-2 rounded-full bg-orange-400 mr-2"></span>
                <span>Terlambat (&gt; Tgl 7)</span>
            </div>
        </div>
    </div>
</nav>