<div class="flex gap-8">
    <!-- Left Panel: Audit Log Timeline -->
    <div class="flex-1">
        <!-- Header & Top Dropdown Controls -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-[#331200] font-headline-md mb-1">Log Audit Sistem</h1>
                <p class="text-sm text-[#897266] font-body-md">Timeline komprehensif dari event sistem, autentikasi, dan aktivitas pesanan.</p>
            </div>

            <!-- Top Filter Action Bar (Image 4 style) -->
            <div class="flex items-center space-x-3 shrink-0">
                <!-- Event Dropdown -->
                <div class="relative">
                    <select wire:model.live="eventType"
                            class="appearance-none bg-[#fff1eb] border border-[#f2dfd5] text-xs font-semibold text-[#8e4e14] pl-4 pr-10 py-2.5 rounded-full focus:outline-none focus:ring-2 focus:ring-[#9b4500] cursor-pointer">
                        <option value="all">Semua Event</option>
                        <option value="auth">Event Auth</option>
                        <option value="order">Event Pesanan</option>
                        <option value="system">Event Sistem</option>
                    </select>
                    <!-- Custom Arrow Icon -->
                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-[#8e4e14]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </span>
                </div>

                <!-- Time Range Dropdown -->
                <div class="relative">
                    <select wire:model.live="timeRange"
                            class="appearance-none bg-[#fff1eb] border border-[#f2dfd5] text-xs font-semibold text-[#8e4e14] pl-4 pr-10 py-2.5 rounded-full focus:outline-none focus:ring-2 focus:ring-[#9b4500] cursor-pointer">
                        <option value="24h">24 Jam Terakhir</option>
                        <option value="7d">7 Hari Terakhir</option>
                        <option value="all">Sepanjang Waktu</option>
                    </select>
                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-[#8e4e14]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </span>
                </div>

                <!-- Export Button -->
                <button class="flex items-center space-x-1.5 bg-[#fff1eb] border border-[#f2dfd5] hover:bg-[#feeae0] text-xs font-semibold text-[#8e4e14] px-4 py-2.5 rounded-full transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    <span>Ekspor</span>
                </button>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl text-sm font-semibold flex items-center">
                <svg class="w-5 h-5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('message') }}
            </div>
        @endif

        <!-- Activity Timeline Container -->
        <div class="bg-white rounded-3xl border border-[#f2dfd5] p-8 shadow-sm">
            <h3 class="text-xl font-bold text-[#331200] font-headline-md mb-8">Timeline Aktivitas</h3>

            <div class="relative">
                <!-- Vertical Line -->
                <div class="absolute left-4 top-2 bottom-2 w-0.5 bg-[#f2dfd5]"></div>

                <!-- Timeline Items -->
                <div class="space-y-6">
                    @forelse($logs as $log)
                        @php
                            $category = 'Auth';
                            $badgeColor = 'bg-[#f49b65] text-[#331200] border-[#f2dfd5]';
                            $dotColor = 'bg-gray-400';
                            
                            if (str_contains($log->action, 'login')) {
                                $category = 'Auth';
                                $badgeColor = 'bg-orange-500/10 text-orange-700 border-orange-200';
                                $dotColor = 'bg-orange-500';
                            } elseif (str_contains($log->action, 'order')) {
                                $category = 'Pesanan';
                                $badgeColor = 'bg-amber-500/10 text-amber-700 border-amber-200';
                                $dotColor = 'bg-amber-500';
                            } elseif (str_contains($log->action, 'system') || str_contains($log->action, 'database')) {
                                $category = 'Sistem';
                                $badgeColor = 'bg-red-500/10 text-red-600 border-red-200';
                                $dotColor = 'bg-red-500';
                            }
                        @endphp

                        <div class="flex items-start relative pl-10">
                            <!-- Dot -->
                            <div class="absolute left-[11px] top-4 w-3.5 h-3.5 rounded-full border-2 border-white ring-4 ring-[#fff8f6] {{ $dotColor }}"></div>

                            <!-- Card item -->
                            <div class="flex-1 bg-[#fff8f6] border border-[#f2dfd5] p-5 rounded-2xl flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                <div class="space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <!-- Category tag badge -->
                                        <span class="px-2 py-0.5 rounded-md text-xxs font-bold uppercase border {{ $badgeColor }}">
                                            {{ $category }}
                                        </span>
                                        <span class="text-sm font-bold text-[#331200]">
                                            @if($log->action === 'auth.login')
                                                Login Berhasil
                                            @elseif($log->action === 'order.refunded')
                                                Pesanan Dikembalikan
                                            @elseif($log->action === 'system.database_backup_failed')
                                                Backup Database Gagal
                                            @elseif($log->action === 'auth.failed')
                                                Percobaan Login Gagal
                                            @else
                                                {{ ucwords(str_replace('.', ' ', $log->action)) }}
                                            @endif
                                        </span>
                                    </div>
                                    <p class="text-sm text-[#897266] leading-relaxed">
                                        {{ $log->description }}
                                    </p>

                                    <!-- If backup failed, render Retry Backup action (Image 4) -->
                                    @if($log->action === 'system.database_backup_failed')
                                        <button wire:click="retryBackup" 
                                                class="mt-2 text-xs font-semibold px-4 py-1.5 rounded-lg border border-red-200 bg-white text-red-500 hover:bg-red-50 transition">
                                            Coba Ulang Backup
                                        </button>
                                    @endif
                                </div>

                                <span class="text-xs text-[#897266] bg-white border border-[#f2dfd5] px-3 py-1 rounded-full shrink-0 font-medium font-sans">
                                    {{ $log->created_at->format('H:i A') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center text-[#897266]">Tidak ada item log yang sesuai dengan pilihan.</div>
                    @endforelse
                </div>
            </div>

            <div class="mt-8 flex justify-center">
                <button wire:click="loadMore"
                        class="w-full bg-[#fff1eb] border border-[#f2dfd5] text-[#8e4e14] font-semibold py-3 px-6 rounded-2xl hover:bg-[#feeae0] transition text-sm">
                    Muat Lebih Banyak Event
                </button>
            </div>
        </div>
    </div>

    <!-- Right Panel: Summary & Filter Sidebar -->
    <div class="w-80 shrink-0 space-y-6">
        <!-- Card 1: Event Summary (Image 4) -->
        <div class="bg-white rounded-3xl border border-[#f2dfd5] p-6 shadow-sm">
            <h3 class="text-lg font-bold text-[#331200] font-headline-md mb-6">Ringkasan Event</h3>
            <div class="grid grid-cols-2 gap-4">
                <!-- Circle Stats 1 -->
                <div class="text-center">
                    <div class="w-20 h-20 rounded-full bg-[#fff1eb] border border-[#f2dfd5] mx-auto flex flex-col items-center justify-center mb-2">
                        <span class="text-2xl font-extrabold text-[#9b4500]">{{ $totalLogsToday }}</span>
                    </div>
                    <span class="text-xs font-semibold text-[#897266] block leading-tight">Total Log Hari Ini</span>
                </div>
                <!-- Circle Stats 2 -->
                <div class="text-center">
                    <div class="w-20 h-20 rounded-full bg-red-50 border border-red-100 mx-auto flex flex-col items-center justify-center mb-2">
                        <span class="text-2xl font-extrabold text-red-500">{{ $criticalErrors }}</span>
                    </div>
                    <span class="text-xs font-semibold text-[#897266] block leading-tight">Error Kritis</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Filter by Role (Image 4) -->
        <div class="bg-white rounded-3xl border border-[#f2dfd5] p-6 shadow-sm">
            <h3 class="text-lg font-bold text-[#331200] font-headline-md mb-4">Filter Berdasarkan Peran</h3>
            <div class="flex flex-wrap gap-2">
                @foreach(['all' => 'Semua Peran', 'admin' => 'Admin', 'vendor' => 'Penjual', 'student' => 'Mahasiswa', 'system' => 'Sistem'] as $key => $label)
                    <button wire:click="setRoleFilter('{{ $key }}')"
                            class="px-4 py-2 rounded-full text-xs font-semibold transition border {{ $roleFilter === $key ? 'bg-[#8e4e14] text-white border-transparent' : 'bg-white text-[#897266] border-[#f2dfd5] hover:bg-[#fff1eb] hover:text-[#9b4500]' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Card 3: Active Admin Sessions (Image 4) -->
        <div class="bg-white rounded-3xl border border-[#f2dfd5] p-6 shadow-sm">
            <h3 class="text-lg font-bold text-[#331200] font-headline-md mb-4">Sesi Admin Aktif</h3>
            <div class="space-y-4">
                @foreach($activeAdmins as $admin)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-9 h-9 rounded-full bg-orange-100 border border-orange-200 flex items-center justify-center font-bold text-orange-700 text-xs">
                                {{ $admin['initial'] }}
                            </div>
                            <span class="text-sm font-semibold text-[#231914]">{{ $admin['name'] }}</span>
                        </div>
                        <span class="w-2.5 h-2.5 bg-green-500 rounded-full border border-white"></span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
