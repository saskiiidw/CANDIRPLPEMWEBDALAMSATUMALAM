<div class="flex min-h-screen bg-[#F7F4F0] font-sans">

    {{-- ═══════════════════════════════════════════
         SIDEBAR
    ═══════════════════════════════════════════ --}}
    <aside class="hidden md:flex flex-col w-64 bg-[#1C1208] text-white flex-shrink-0 sticky top-0 h-screen">

        {{-- Logo --}}
        <div class="px-6 py-7 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-[#E8813A] rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-white/50 leading-none mb-0.5">CampusBites</p>
                    <p class="text-sm font-bold leading-none text-white">Seller Panel</p>
                </div>
            </div>
        </div>

        {{-- Toko Info --}}
        <div class="px-6 py-5 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#E8813A] to-[#9b4500] flex items-center justify-center font-bold text-sm text-white flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->store_name ?? auth()->user()->name, 0, 2)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-bold text-white truncate">{{ auth()->user()->store_name ?? auth()->user()->name }}</p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>
                        <span class="text-xs text-white/50">Buka Sekarang</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-4 py-5 space-y-1 overflow-y-auto">
            <p class="px-3 mb-3 text-[10px] font-bold tracking-widest text-white/30 uppercase">Menu Utama</p>

            <a href="{{ route('seller.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all bg-[#E8813A]/20 text-[#E8813A] border border-[#E8813A]/30">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                Dashboard Live
            </a>

            <a href="{{ route('seller.sales-report') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all text-white/60 hover:bg-white/5 hover:text-white">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Laporan Penjualan
            </a>

            <a href="{{ route('profile') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all text-white/60 hover:bg-white/5 hover:text-white">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Profil Toko
            </a>
        </nav>

        {{-- Logout --}}
        <div class="px-4 py-5 border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="flex items-center gap-3 w-full px-4 py-3 rounded-xl text-sm font-semibold text-white/60 hover:bg-red-500/10 hover:text-red-400 transition-all">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- ═══════════════════════════════════════════
         MAIN CONTENT
    ═══════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">

        {{-- Top Bar --}}
        <header class="sticky top-0 z-10 bg-white/90 backdrop-blur-sm border-b border-gray-100 px-8 py-4 flex items-center justify-between flex-shrink-0">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Live Order Feed</h1>
                <p class="text-xs text-gray-400 mt-0.5">Pesanan masuk diperbarui secara otomatis <span class="text-green-500 font-medium">(Real-time)</span></p>
            </div>
            <div class="flex items-center gap-4">
                {{-- Live Badge --}}
                <div class="flex items-center gap-2 bg-green-50 border border-green-200 px-4 py-2 rounded-full">
                    <span class="relative flex w-2.5 h-2.5">
                        <span class="absolute inline-flex w-full h-full bg-green-400 rounded-full opacity-75 animate-ping"></span>
                        <span class="relative inline-flex w-2.5 h-2.5 bg-green-500 rounded-full"></span>
                    </span>
                    <span class="text-sm font-semibold text-green-700">{{ count($orders) }} Pesanan Aktif</span>
                </div>

                {{-- User info --}}
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#E8813A] to-[#9b4500] flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-sm font-semibold text-gray-800 leading-none">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Penjual</p>
                    </div>
                </div>
            </div>
        </header>

        {{-- Stats Row --}}
        <div class="px-8 pt-8 grid grid-cols-2 lg:grid-cols-4 gap-4">
            @php
                $totalToday  = \App\Models\Order::where('seller_id', auth()->id())
                                ->whereDate('created_at', today())
                                ->count();
                $revenueToday = \App\Models\Order::where('seller_id', auth()->id())
                                ->whereDate('created_at', today())
                                ->where('status', 'selesai')
                                ->sum('total_price');
                $pendingCount = \App\Models\Order::where('seller_id', auth()->id())
                                ->whereIn('status', ['diterima'])
                                ->count();
                $processingCount = \App\Models\Order::where('seller_id', auth()->id())
                                ->where('status', 'diproses')
                                ->count();
            @endphp

            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Pesanan Hari Ini</span>
                    <div class="w-8 h-8 bg-blue-50 rounded-xl flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-gray-900">{{ $totalToday }}</p>
                <p class="text-xs text-gray-400 mt-1">Total masuk hari ini</p>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Pendapatan</span>
                    <div class="w-8 h-8 bg-green-50 rounded-xl flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-extrabold text-gray-900">Rp {{ number_format($revenueToday, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-400 mt-1">Pesanan selesai hari ini</p>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Menunggu Konfirmasi</span>
                    <div class="w-8 h-8 bg-orange-50 rounded-xl flex items-center justify-center">
                        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-gray-900">{{ $pendingCount }}</p>
                <p class="text-xs text-gray-400 mt-1">Pesanan baru masuk</p>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Sedang Dimasak</span>
                    <div class="w-8 h-8 bg-purple-50 rounded-xl flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-gray-900">{{ $processingCount }}</p>
                <p class="text-xs text-gray-400 mt-1">Sedang dalam proses</p>
            </div>
        </div>

        {{-- Order Grid --}}
        <div class="px-8 py-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-lg font-bold text-gray-800">Antrian Pesanan Aktif</h2>
                <span class="text-xs text-gray-400">Auto-refresh setiap 5 detik</span>
            </div>

            <div class="grid grid-cols-1 gap-5 xl:grid-cols-2" wire:poll.5000ms>
                @forelse($orders as $order)
                    <div class="flex flex-col bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden"
                         wire:key="order-{{ $order->id }}">

                        {{-- Order Header --}}
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <span class="text-sm font-bold text-gray-600">#{{ $order->id }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ $order->buyer->name ?? 'Mahasiswa' }}</p>
                                    <p class="text-xs text-gray-400">{{ $order->created_at->format('H:i') }} WIB · {{ $order->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            @if($order->status === 'diterima')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-orange-700 bg-orange-100 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-orange-500 rounded-full animate-pulse"></span>
                                    Pesanan Baru
                                </span>
                            @elseif($order->status === 'diproses')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-blue-700 bg-blue-100 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></span>
                                    Sedang Dimasak
                                </span>
                            @elseif($order->status === 'siap_diambil')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-green-700 bg-green-100 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                    Siap Diambil
                                </span>
                            @endif
                        </div>

                        {{-- Items --}}
                        <div class="px-6 py-4 flex-1 space-y-2">
                            @foreach($order->items as $item)
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start gap-3">
                                        <span class="mt-0.5 w-6 h-6 bg-[#E8813A]/10 text-[#E8813A] text-xs font-bold rounded-lg flex items-center justify-center flex-shrink-0">
                                            {{ $item->quantity }}
                                        </span>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">{{ $item->menu_name_snapshot ?? ($item->menu->name ?? 'Item') }}</p>
                                            @if($item->notes)
                                                <p class="text-xs text-gray-400 mt-0.5">📝 {{ $item->notes }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <span class="text-sm font-bold text-gray-700">Rp {{ number_format($item->price_snapshot * $item->quantity, 0, ',', '.') }}</span>
                                </div>
                            @endforeach

                            @if($order->note)
                                <div class="mt-3 p-3 bg-amber-50 rounded-xl border border-amber-100">
                                    <p class="text-xs text-amber-700 font-medium">💬 Catatan: {{ $order->note }}</p>
                                </div>
                            @endif
                        </div>

                        {{-- Footer: total + actions --}}
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                            <div>
                                <p class="text-xs text-gray-400">Total Pembayaran</p>
                                <p class="text-lg font-extrabold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </div>

                            <div class="flex gap-2 w-full sm:w-auto">
                                @if(in_array($order->status, ['diterima', 'diproses']))
                                    <button wire:click="reject({{ $order->id }})"
                                            wire:loading.attr="disabled"
                                            class="flex-1 sm:flex-none px-4 py-2.5 text-sm font-semibold text-red-600 bg-white border border-red-200 rounded-xl hover:bg-red-50 transition-colors disabled:opacity-50">
                                        <span wire:loading.remove wire:target="reject({{ $order->id }})">Tolak</span>
                                        <span wire:loading wire:target="reject({{ $order->id }})">...</span>
                                    </button>
                                @endif

                                @if($order->status === 'diterima')
                                    <button wire:click="process({{ $order->id }})"
                                            wire:loading.attr="disabled"
                                            class="flex-1 sm:flex-none px-5 py-2.5 text-sm font-bold text-white bg-[#E8813A] hover:bg-[#d4712d] rounded-xl shadow-sm transition-colors disabled:opacity-50">
                                        <span wire:loading.remove wire:target="process({{ $order->id }})">✓ Proses Pesanan</span>
                                        <span wire:loading wire:target="process({{ $order->id }})">Memproses...</span>
                                    </button>
                                @elseif($order->status === 'diproses')
                                    <button wire:click="markReady({{ $order->id }})"
                                            wire:loading.attr="disabled"
                                            class="flex-1 sm:flex-none px-5 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition-colors disabled:opacity-50">
                                        <span wire:loading.remove wire:target="markReady({{ $order->id }})">🔔 Siap Diambil</span>
                                        <span wire:loading wire:target="markReady({{ $order->id }})">Memproses...</span>
                                    </button>
                                @elseif($order->status === 'siap_diambil')
                                    <button disabled class="flex-1 sm:flex-none px-5 py-2.5 text-sm font-semibold text-gray-400 bg-gray-200 rounded-xl cursor-not-allowed">
                                        Menunggu Diambil
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="xl:col-span-2">
                        <div class="flex flex-col items-center justify-center py-24 bg-white rounded-2xl border border-dashed border-gray-200">
                            <div class="w-20 h-20 bg-gray-50 rounded-2xl flex items-center justify-center mb-5">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-700 mb-1">Belum Ada Pesanan Aktif</h3>
                            <p class="text-sm text-gray-400 text-center max-w-xs">
                                Pesanan baru dari mahasiswa akan otomatis muncul di sini tanpa perlu refresh halaman.
                            </p>
                            <div class="mt-6 flex items-center gap-2 text-xs text-gray-400">
                                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                                Menunggu pesanan masuk...
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
