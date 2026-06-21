<div class="flex flex-col min-h-screen bg-gray-50 md:flex-row">
    <aside class="w-full bg-white border-r border-gray-200 md:w-64 md:min-h-screen">
        <div class="flex items-center justify-between p-6 md:block">
            <h1 class="text-xl font-bold text-orange-600">Kantin Manager</h1>
        </div>
        <nav class="hidden px-4 pb-6 space-y-2 md:block">
            <a href="#" class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard Live
            </a>
            <a href="#" class="flex items-center px-4 py-3 text-orange-700 bg-orange-100 rounded-lg">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Laporan Penjualan
            </a>
        </nav>
    </aside>

    <main class="flex-1 p-6">
        <div class="flex flex-col items-start justify-between mb-8 space-y-4 md:flex-row md:items-center md:space-y-0">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Ringkasan Laporan</h2>
                <p class="text-sm text-gray-500">Evaluasi performa penjualan kantin (Hanya pesanan berstatus Selesai)</p>
            </div>
            
            <div class="flex items-center p-1 bg-gray-200 rounded-lg">
                <label class="relative cursor-pointer">
                    <input type="radio" wire:model.live="period" value="harian" class="sr-only peer">
                    <div class="px-4 py-1.5 text-sm font-medium rounded-md transition-colors text-gray-500 peer-checked:bg-white peer-checked:text-gray-900 peer-checked:shadow-sm">
                        Hari Ini
                    </div>
                </label>
                <label class="relative cursor-pointer ml-1">
                    <input type="radio" wire:model.live="period" value="mingguan" class="sr-only peer">
                    <div class="px-4 py-1.5 text-sm font-medium rounded-md transition-colors text-gray-500 peer-checked:bg-white peer-checked:text-gray-900 peer-checked:shadow-sm">
                        Minggu Ini
                    </div>
                </label>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3" wire:loading.class="opacity-50 pointer-events-none transition-opacity duration-200">
            <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Total Pendapatan</h3>
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="flex items-baseline">
                    <p class="text-3xl font-bold text-gray-900">Rp {{ number_format($this->summary['total_revenue'], 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Pesanan Sukses</h3>
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                </div>
                <div class="flex items-baseline">
                    <p class="text-3xl font-bold text-gray-900">{{ $this->summary['total_orders'] }} <span class="text-base font-medium text-gray-500">Order</span></p>
                </div>
            </div>

            <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Menu Terlaris</h3>
                    <div class="p-2 bg-orange-100 rounded-lg">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    </div>
                </div>
                <div class="flex items-baseline">
                    <p class="text-2xl font-bold text-gray-900 truncate" title="{{ $this->summary['best_seller'] }}">{{ $this->summary['best_seller'] }}</p>
                </div>
            </div>
        </div>
    </main>
</div>