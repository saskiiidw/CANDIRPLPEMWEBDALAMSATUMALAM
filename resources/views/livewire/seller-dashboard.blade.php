<div class="flex min-h-screen bg-[#FFFBF7] font-sans text-[#331C0E]" x-data="{ openMenuModal: @entangle('showMenuModal') }">
    
    <!-- Sidebar Navigation -->
    <aside class="hidden md:flex flex-col w-64 bg-[#FFF1E5] border-r border-[#F4E1D2] flex-shrink-0 sticky top-0 h-screen justify-between p-6">
        <div class="space-y-6">
            <!-- Store Profile Header -->
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-full bg-gradient-to-br from-[#E27226] to-[#9E460B] flex items-center justify-center font-bold text-white shadow-md text-base">
                    {{ strtoupper(substr($storeName ?: auth()->user()->name, 0, 2)) }}
                </div>
                <div class="min-w-0">
                    <h2 class="text-base font-extrabold text-[#331C0E] truncate leading-tight font-display">{{ $storeName ?: 'My Kitchen' }}</h2>
                    <span class="text-xs text-[#8A7160] flex items-center gap-1.5 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full {{ auth()->user()->is_active ? 'bg-green-500 animate-pulse' : 'bg-gray-400' }}"></span>
                        {{ auth()->user()->is_active ? 'Sesi Aktif' : 'Tutup' }}
                    </span>
                </div>
            </div>

            <!-- Add New Item button -->
            <button wire:click="openMenuModal" 
                    class="w-full bg-[#9E460B] hover:bg-[#803708] text-white py-3.5 px-4 rounded-2xl font-bold transition flex items-center justify-center gap-2 text-xs shadow-sm active:scale-95 duration-150">
                <span class="material-symbols-outlined text-sm font-bold">add</span>
                <span>Tambah Menu Baru</span>
            </button>

            <!-- Navigation Links -->
            <nav class="space-y-1 pt-4">
                <button wire:click="$set('activeTab', 'dashboard')" 
                        class="w-full flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-xs font-bold transition-all duration-150 {{ $activeTab === 'dashboard' ? 'bg-[#E27226] text-white shadow-sm' : 'text-[#8A7160] hover:bg-[#FFF8F2] hover:text-[#9E460B]' }}">
                    <span class="material-symbols-outlined text-lg">dashboard</span>
                    <span>Dasbor</span>
                </button>
                <button wire:click="$set('activeTab', 'menu_stock')" 
                        class="w-full flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-xs font-bold transition-all duration-150 {{ $activeTab === 'menu_stock' ? 'bg-[#E27226] text-white shadow-sm' : 'text-[#8A7160] hover:bg-[#FFF8F2] hover:text-[#9E460B]' }}">
                    <span class="material-symbols-outlined text-lg">restaurant_menu</span>
                    <span>Menu & Stok</span>
                </button>
                <button wire:click="$set('activeTab', 'reports')" 
                        class="w-full flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-xs font-bold transition-all duration-150 {{ $activeTab === 'reports' ? 'bg-[#E27226] text-white shadow-sm' : 'text-[#8A7160] hover:bg-[#FFF8F2] hover:text-[#9E460B]' }}">
                    <span class="material-symbols-outlined text-lg">monitoring</span>
                    <span>Laporan</span>
                </button>
                <button wire:click="$set('activeTab', 'profile')" 
                        class="w-full flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-xs font-bold transition-all duration-150 {{ $activeTab === 'profile' ? 'bg-[#E27226] text-white shadow-sm' : 'text-[#8A7160] hover:bg-[#FFF8F2] hover:text-[#9E460B]' }}">
                    <span class="material-symbols-outlined text-lg">person</span>
                    <span>Profil</span>
                </button>
            </nav>
        </div>

        <!-- Support & Logout -->
        <div class="space-y-1">
            <button class="w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl text-xs font-bold text-[#8A7160] hover:bg-[#FFF8F2] hover:text-[#9E460B] transition-all">
                <span class="material-symbols-outlined text-lg">help</span>
                <span>Bantuan</span>
            </button>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl text-xs font-bold text-[#8A7160] hover:bg-red-50 hover:text-red-650 transition-all">
                    <span class="material-symbols-outlined text-lg">logout</span>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Panel -->
    <div class="flex-grow flex flex-col min-w-0 min-h-screen overflow-y-auto">
        <!-- Top Navigation -->
        <header class="bg-white border-b border-[#F4E1D2] h-20 px-8 flex items-center justify-between sticky top-0 z-25">
            <div>
                <h1 class="text-lg font-extrabold text-[#331C0E] font-display">Manajer Kantin</h1>
            </div>
            
            <div class="flex items-center gap-6" x-data="{ notifOpen: false }" @click.away="notifOpen = false">
                <!-- Store Status Toggle -->
                <div class="flex items-center gap-3 bg-[#FFF8F2] border border-[#F4E1D2] px-4 py-2 rounded-full">
                    <span class="text-xs font-bold text-[#8A7160]">Toko Buka</span>
                    <button wire:click="toggleStoreStatus" 
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ auth()->user()->is_active ? 'bg-[#E27226]' : 'bg-gray-200' }}">
                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ auth()->user()->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                    </button>
                </div>

                <!-- Notification Bell with Dropdown -->
                <div class="relative">
                    <button @click="notifOpen = !notifOpen"
                            class="relative p-2 text-[#8A7160] hover:bg-[#FFF8F2] rounded-xl transition">
                        <span class="material-symbols-outlined text-xl">notifications</span>
                        @if(count($orders) > 0)
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-[#E27226] rounded-full border border-white animate-pulse"></span>
                        @endif
                    </button>

                    <!-- Notification Dropdown -->
                    <div x-show="notifOpen"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 top-full mt-2 w-80 bg-white border border-[#F4E1D2] rounded-2xl shadow-xl py-2 z-30"
                         x-cloak>
                        <div class="px-4 py-3 border-b border-[#F4E1D2] flex items-center justify-between">
                            <p class="text-xs font-extrabold text-[#331C0E]">Pesanan Aktif</p>
                            @if(count($orders) > 0)
                                <span class="bg-[#E27226] text-white text-[9px] font-extrabold px-2 py-0.5 rounded-full">{{ count($orders) }} baru</span>
                            @endif
                        </div>
                        <div class="max-h-64 overflow-y-auto">
                            @forelse($orders as $o)
                                <button @click="notifOpen = false" wire:click="selectOrder({{ $o->id }})"
                                        class="w-full flex items-start gap-3 px-4 py-3 hover:bg-[#FFF8F2] transition text-left">
                                    <span class="material-symbols-outlined text-lg shrink-0 mt-0.5 {{ $o->status === 'diterima' ? 'text-[#E27226]' : ($o->status === 'diproses' ? 'text-blue-500' : 'text-green-500') }}">
                                        {{ $o->status === 'diterima' ? 'new_releases' : ($o->status === 'diproses' ? 'soup_kitchen' : 'check_circle') }}
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-[#331C0E]">Order #{{ $o->id }}</p>
                                        <p class="text-[10px] text-[#8A7160] truncate">{{ $o->buyer->name }} · {{ $o->created_at->diffForHumans() }}</p>
                                        <span class="text-[10px] font-bold {{ $o->status === 'diterima' ? 'text-[#E27226]' : ($o->status === 'diproses' ? 'text-blue-600' : 'text-green-600') }}">
                                            {{ ucfirst(str_replace('_',' ',$o->status)) }}
                                        </span>
                                    </div>
                                </button>
                            @empty
                                <div class="px-4 py-6 text-center text-[#8A7160]">
                                    <span class="material-symbols-outlined text-3xl text-gray-300">notifications_none</span>
                                    <p class="text-xs font-bold mt-1">Tidak ada pesanan aktif</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="border-t border-[#F4E1D2] px-4 py-2">
                            <button @click="notifOpen = false" wire:click="$set('activeTab','dashboard')"
                                    class="text-xs font-bold text-[#9E460B] hover:underline">Lihat semua pesanan →</button>
                        </div>
                    </div>
                </div>

                <!-- Settings -->
                <button wire:click="$set('activeTab', 'profile')" class="p-2 text-[#8A7160] hover:bg-[#FFF8F2] rounded-xl transition" title="Settings">
                    <span class="material-symbols-outlined text-xl">settings</span>
                </button>

                <!-- Avatar Profile with Dropdown -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" class="w-9 h-9 rounded-full bg-orange-100 border border-[#F4E1D2] overflow-hidden focus:outline-none focus:ring-2 focus:ring-[#E27226]/50 flex items-center justify-center">
                        <img src="https://api.dicebear.com/7.x/adventurer/svg?seed={{ auth()->user()->name }}" alt="avatar" class="w-full h-full object-cover">
                    </button>

                    <!-- Dropdown Panel -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white border border-[#F4E1D2] rounded-2xl shadow-xl py-2 z-30"
                         x-cloak>
                        
                        <!-- User Info -->
                        <div class="px-4 py-2 border-b border-[#F4E1D2] mb-1">
                            <p class="text-xs font-bold text-[#331C0E] truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-[#8A7160] truncate">{{ auth()->user()->email }}</p>
                        </div>

                        <!-- Dropdown Items -->
                        <button @click="open = false" wire:click="$set('activeTab', 'profile')" 
                                class="w-full text-left px-4 py-2.5 text-xs text-[#8A7160] hover:bg-[#FFF8F2] hover:text-[#9E460B] font-bold transition flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">person</span>
                            <span>Lihat Profil</span>
                        </button>

                        <div class="border-t border-[#F4E1D2] my-1"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="w-full text-left px-4 py-2.5 text-xs text-red-600 hover:bg-red-50 font-bold transition flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">logout</span>
                                <span>Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow p-8">
            
            @if (session()->has('message'))
                <div class="mb-6 p-4 bg-[#FFF8F2] border border-[#F4E1D2] text-[#9E460B] rounded-2xl text-xs font-bold flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">check_circle</span>
                    <span>{{ session('message') }}</span>
                </div>
            @endif

            <!-- 1. DASHBOARD TAB -->
            @if($activeTab === 'dashboard')
                @if(!$selectedOrderId)
                    <!-- Dashboard Feed Grid -->
                    <div class="space-y-8">
                        <div>
                            <h2 class="text-2xl font-extrabold text-[#331C0E] font-display">Ringkasan Operasional</h2>
                            <p class="text-xs text-[#8A7160] mt-1">Kinerja kantin real-time dan pesanan aktif.</p>
                        </div>

                        <!-- Stats Row -->
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                            <!-- Stat 1 -->
                            <div class="bg-[#E27226] rounded-[24px] p-6 text-white shadow-sm flex flex-col justify-between h-36">
                                <div class="flex items-center justify-between">
                                    <span class="material-symbols-outlined text-xl">new_releases</span>
                                    <span class="bg-white/20 px-2 py-0.5 rounded-full text-[10px] font-bold">+12%</span>
                                </div>
                                <div>
                                    <span class="text-xs font-bold opacity-90 uppercase">Pesanan Baru</span>
                                    <p class="text-3xl font-extrabold mt-1 font-display">{{ $stats['new_orders'] }}</p>
                                </div>
                            </div>
                            <!-- Stat 2 -->
                            <div class="bg-[#FFF1E5] border border-[#F4E1D2] rounded-[24px] p-6 shadow-sm flex flex-col justify-between h-36">
                                <div class="flex items-center justify-between">
                                    <span class="material-symbols-outlined text-xl text-[#9E460B]">soup_kitchen</span>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-[#8A7160] uppercase">Diproses</span>
                                    <p class="text-3xl font-extrabold mt-1 text-[#331C0E] font-display">{{ $stats['processing'] }}</p>
                                </div>
                            </div>
                            <!-- Stat 3 -->
                            <div class="bg-[#FFF1E5] border border-[#F4E1D2] rounded-[24px] p-6 shadow-sm flex flex-col justify-between h-36">
                                <div class="flex items-center justify-between">
                                    <span class="material-symbols-outlined text-xl text-[#9E460B]">inventory_2</span>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-[#8A7160] uppercase">Siap Diambil</span>
                                    <p class="text-3xl font-extrabold mt-1 text-[#331C0E] font-display">{{ $stats['ready'] }}</p>
                                </div>
                            </div>
                            <!-- Stat 4 -->
                            <div class="bg-[#FFF1E5] border border-[#F4E1D2] rounded-[24px] p-6 shadow-sm flex flex-col justify-between h-36">
                                <div class="flex items-center justify-between">
                                    <span class="material-symbols-outlined text-xl text-[#9E460B]">done_all</span>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-[#8A7160] uppercase">Selesai</span>
                                    <p class="text-3xl font-extrabold mt-1 text-[#331C0E] font-display">{{ $stats['completed'] }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Today's Revenue card -->
                        <div class="bg-white border border-[#F4E1D2] rounded-[24px] p-6 max-w-sm flex items-center justify-between shadow-sm relative overflow-hidden">
                            <div class="space-y-1 relative z-10">
                                <div class="flex items-center gap-1 text-xs text-[#8A7160] font-bold">
                                    <span>Pendapatan Hari Ini</span>
                                    <span class="material-symbols-outlined text-sm">info</span>
                                </div>
                                <p class="text-3xl font-extrabold text-[#331C0E] font-display">Rp {{ number_format($stats['today_revenue'], 0, ',', '.') }}</p>
                                <span class="text-xs text-green-600 font-bold flex items-center gap-1">
                                    <span class="material-symbols-outlined text-xs">trending_up</span> 8% vs kemarin
                                </span>
                            </div>
                            <div class="w-16 h-16 bg-[#FFF1E5] text-[#9E460B] rounded-2xl flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-3xl">payments</span>
                            </div>
                        </div>

                        <!-- Live Order Feed table -->
                        <div class="space-y-4 pt-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-[#331C0E] font-display">Daftar Pesanan Langsung</h3>
                                <button class="text-xs font-bold text-[#9E460B] hover:underline">Lihat Semua Riwayat</button>
                            </div>

                            <div class="bg-white rounded-[24px] border border-[#F4E1D2] shadow-sm overflow-hidden" wire:poll.5000ms>
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-[#FFF8F2] border-b border-[#F4E1D2] text-xs font-bold text-[#8A7160] uppercase tracking-wider">
                                            <th class="px-6 py-4.5">ID Pesanan</th>
                                            <th class="px-6 py-4.5">Ringkasan Item</th>
                                            <th class="px-6 py-4.5">Waktu</th>
                                            <th class="px-6 py-4.5">Status</th>
                                            <th class="px-6 py-4.5 text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#F4E1D2] text-sm text-[#331C0E]">
                                        @forelse($orders as $order)
                                            <tr wire:click="selectOrder({{ $order->id }})" class="hover:bg-[#FFF8F2]/50 cursor-pointer transition">
                                                <td class="px-6 py-4 font-bold text-xs">#ORD-{{ $order->id }}</td>
                                                <td class="px-6 py-4">
                                                    <p class="font-bold truncate max-w-md">
                                                        @foreach($order->items as $item)
                                                            {{ $item->quantity }}x {{ $item->menu_name_snapshot ?? ($item->menu->name ?? 'Item') }}{{ !$loop->last ? ', ' : '' }}
                                                        @endforeach
                                                    </p>
                                                </td>
                                                <td class="px-6 py-4 text-xs text-[#8A7160]">{{ $order->created_at->diffForHumans() }}</td>
                                                <td class="px-6 py-4">
                                                    @if($order->status === 'diterima')
                                                        <span class="bg-orange-100 text-[#E27226] px-3 py-1 rounded-full text-xs font-bold">Pesanan Baru</span>
                                                    @elseif($order->status === 'diproses')
                                                        <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">Diproses</span>
                                                    @elseif($order->status === 'siap_diambil')
                                                        <span class="bg-green-50 text-green-700 px-3 py-1 rounded-full text-xs font-bold">Siap</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    <div class="flex justify-end gap-2" @click.stop>
                                                        @if($order->status === 'diterima')
                                                            <button wire:click="process({{ $order->id }})" class="bg-[#9E460B] hover:bg-[#803708] text-white text-xs font-bold px-3 py-1.5 rounded-xl transition">
                                                                Terima
                                                            </button>
                                                        @elseif($order->status === 'diproses')
                                                            <button wire:click="markReady({{ $order->id }})" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-3 py-1.5 rounded-xl transition">
                                                                Tandai Siap
                                                            </button>
                                                        @elseif($order->status === 'siap_diambil')
                                                            <button wire:click="completeOrder({{ $order->id }})" class="bg-green-600 hover:bg-green-700 text-white text-xs font-bold px-3 py-1.5 rounded-xl transition">
                                                                Selesai
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-16 text-[#8A7160]">
                                                    <span class="material-symbols-outlined text-4xl text-gray-300">receipt_long</span>
                                                    <p class="text-sm font-bold mt-2">Tidak ada pesanan aktif saat ini.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Detailed Order view (Mockup Image 2) -->
                    <div class="space-y-6">
                        <!-- Navigation Header -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <button wire:click="selectOrder(null)" class="w-10 h-10 bg-white border border-[#F4E1D2] rounded-full flex items-center justify-center hover:bg-[#FFF8F2] transition">
                                    <span class="material-symbols-outlined text-lg">arrow_back</span>
                                </button>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h2 class="text-2xl font-extrabold text-[#331C0E] font-display">Pesanan #{{ $selectedOrder->id }}-B</h2>
                                        <span class="bg-orange-100 text-[#E27226] text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">{{ $selectedOrder->status }}</span>
                                    </div>
                                    <p class="text-xs text-[#8A7160] mt-0.5">Dipesan {{ $selectedOrder->created_at->format('M d, Y \a\t H:i A') }}</p>
                                </div>
                            </div>

                            <!-- Reject / Accept buttons -->
                            <div class="flex gap-3">
                                @if($selectedOrder->status === 'diterima')
                                    <button wire:click="reject({{ $selectedOrder->id }})" class="bg-red-50 text-[#C0392B] border border-red-200 text-xs font-bold px-4 py-2.5 rounded-xl hover:bg-red-100 transition">
                                        Tolak Pesanan
                                    </button>
                                    <button wire:click="process({{ $selectedOrder->id }})" class="bg-[#9E460B] hover:bg-[#803708] text-white text-xs font-bold px-5 py-2.5 rounded-xl transition flex items-center gap-1.5 shadow-sm">
                                        <span class="material-symbols-outlined text-sm font-bold">check</span>
                                        <span>Terima Pesanan</span>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Details Grid -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <!-- Left columns -->
                            <div class="lg:col-span-2 space-y-6">
                                <!-- Order Items -->
                                <div class="bg-white border border-[#F4E1D2] rounded-[32px] p-6 shadow-sm space-y-6">
                                    <h3 class="text-base font-extrabold text-[#331C0E] font-display">Item Pesanan ({{ count($selectedOrder->items) }})</h3>
                                    
                                    <div class="divide-y divide-[#F4E1D2]">
                                        @foreach($selectedOrder->items as $item)
                                            <div class="flex items-start justify-between py-4 first:pt-0 last:pb-0">
                                                <div class="flex gap-4">
                                                    <div class="w-14 h-14 bg-[#FFF8F2] rounded-2xl flex items-center justify-center border border-[#F4E1D2] text-[#9E460B] font-bold text-xs shrink-0 overflow-hidden">
                                                        <span class="material-symbols-outlined text-2xl">lunch_dining</span>
                                                    </div>
                                                    <div>
                                                        <h4 class="font-extrabold text-[#331C0E] text-sm">{{ $item->menu_name_snapshot ?? ($item->menu->name ?? 'Menu Item') }}</h4>
                                                        <p class="text-xs text-[#8A7160] mt-0.5">Modifikasi: {{ $item->notes ?: 'Tidak ada' }}</p>
                                                        <div class="flex gap-2 mt-2">
                                                            <span class="bg-[#FFF1E5] text-[#9E460B] px-2 py-0.5 rounded text-[10px] font-bold uppercase">Qty: {{ $item->quantity }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="font-extrabold text-[#331C0E] text-sm">Rp {{ number_format($item->price_snapshot * $item->quantity, 0, ',', '.') }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Customer Notes -->
                                <div class="bg-white border border-[#F4E1D2] rounded-[32px] p-6 shadow-sm space-y-3">
                                    <div class="flex items-center gap-2 text-xs font-bold text-[#8A7160] uppercase">
                                        <span class="material-symbols-outlined text-base">sticky_note_2</span>
                                        <span>Catatan Pelanggan</span>
                                    </div>
                                    <div class="bg-[#FFF8F2] border border-[#F4E1D2] rounded-2xl p-4 text-xs italic text-[#8A7160]">
                                        "{{ $selectedOrder->note ?: 'Tidak ada catatan dari pelanggan.' }}"
                                    </div>
                                </div>
                            </div>

                            <!-- Right columns -->
                            <div class="space-y-6">
                                <!-- Target Prep Time -->
                                <div class="bg-gradient-to-br from-[#FFF1E5] to-[#FCD8BD] border border-[#F4E1D2] rounded-[32px] p-6 shadow-sm space-y-4">
                                    <span class="text-xs font-bold text-[#8A7160] uppercase tracking-wider">Target Waktu Persiapan</span>
                                    <div class="text-center py-4">
                                        <p class="text-5xl font-extrabold text-[#9E460B] font-display">{{ $selectedOrder->eta_minutes ?: 12 }} <span class="text-base font-bold text-[#8A7160]">mnt</span></p>
                                    </div>
                                    <div class="w-full bg-white/50 h-2 rounded-full overflow-hidden">
                                        <div class="bg-[#E27226] h-full w-2/3 rounded-full"></div>
                                    </div>
                                    
                                    <div class="flex gap-2 pt-2">
                                        @if($selectedOrder->status === 'diproses')
                                            <button wire:click="markReady({{ $selectedOrder->id }})" class="w-full bg-[#E27226] hover:bg-[#c95d12] text-white py-3 rounded-xl text-xs font-bold shadow-sm transition">
                                                Tandai Siap
                                            </button>
                                        @elseif($selectedOrder->status === 'siap_diambil')
                                            <button wire:click="completeOrder({{ $selectedOrder->id }})" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl text-xs font-bold shadow-sm transition">
                                                Selesaikan Pesanan
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <!-- Student Card -->
                                <div class="bg-white border border-[#F4E1D2] rounded-[32px] p-6 shadow-sm flex flex-col items-center text-center space-y-4">
                                    <img class="w-16 h-16 rounded-full object-cover border border-[#F4E1D2] bg-orange-50" src="https://api.dicebear.com/7.x/adventurer/svg?seed={{ $selectedOrder->buyer->name }}" alt="Student">
                                    <div>
                                        <h4 class="font-extrabold text-[#331C0E] text-base leading-tight font-display">{{ $selectedOrder->buyer->name }}</h4>
                                        <p class="text-xs text-[#8A7160] mt-0.5">Pelanggan · {{ explode('@', $selectedOrder->buyer->email)[0] }}</p>
                                    </div>
                                    
                                    <div class="w-full text-left space-y-2 text-xs border-t border-[#F4E1D2] pt-4">
                                        <div class="flex justify-between">
                                            <span class="text-[#8A7160]">Email:</span>
                                            <span class="font-bold text-[#331C0E]">{{ $selectedOrder->buyer->email }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-[#8A7160]">Peran:</span>
                                            <span class="font-bold text-[#331C0E] uppercase">{{ $selectedOrder->buyer->role }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Summary -->
                                <div class="bg-white border border-[#F4E1D2] rounded-[32px] p-6 shadow-sm space-y-4">
                                    <span class="text-xs font-bold text-[#8A7160] uppercase">Ringkasan Pembayaran</span>
                                    <div class="space-y-2 text-xs border-b border-[#F4E1D2] pb-3">
                                        <div class="flex justify-between">
                                            <span class="text-[#8A7160]">Subtotal</span>
                                            <span class="font-bold">Rp {{ number_format($selectedOrder->total_price, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between text-green-600">
                                            <span>Diskon</span>
                                            <span>-Rp 0</span>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center text-sm font-extrabold">
                                        <span>Total</span>
                                        <span class="text-base text-[#9E460B] font-display">Rp {{ number_format($selectedOrder->total_price, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="bg-green-50 border border-green-200 text-green-700 p-3 rounded-2xl text-[11px] font-bold flex items-center justify-center gap-1">
                                        <span class="material-symbols-outlined text-sm font-bold">verified</span>
                                        <span>Dibayar via SmartWallet</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            <!-- 2. MENU & STOCK TAB (Mockup Image 3) -->
            @if($activeTab === 'menu_stock')
                <div class="space-y-8">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-extrabold text-[#331C0E] font-display">Inventaris</h2>
                            <p class="text-xs text-[#8A7160] mt-1">Kelola menu dan stok secara real-time.</p>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <!-- Search -->
                            <div class="relative max-w-xs">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#8A7160] text-lg">search</span>
                                <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="Cari item..." 
                                       class="w-full pl-9 pr-4 py-2.5 bg-white border border-[#F4E1D2] rounded-full text-xs text-[#331C0E] focus:ring-2 focus:ring-[#E27226]/50 transition">
                            </div>
                            
                            <!-- Add Item -->
                            <button wire:click="openMenuModal" class="bg-[#E27226] hover:bg-[#c95d12] text-white text-xs font-bold px-4 py-2.5 rounded-full transition flex items-center gap-1 shadow-sm">
                                <span class="material-symbols-outlined text-sm font-bold">add</span>
                                <span>Tambah Item</span>
                            </button>
                        </div>
                    </div>

                    <!-- Category Chips -->
                    <div class="flex gap-2 overflow-x-auto pb-1">
                        <button wire:click="$set('selectedCategory', 'all')" class="px-4 py-2 rounded-full text-xs font-bold border transition shrink-0 {{ $selectedCategory === 'all' ? 'bg-[#9E460B] border-[#9E460B] text-white shadow-sm' : 'border-[#F4E1D2] text-[#8A7160] hover:bg-white' }}">
                            Semua Item
                        </button>
                        <button wire:click="$set('selectedCategory', 'makanan_berat')" class="px-4 py-2 rounded-full text-xs font-bold border transition shrink-0 {{ $selectedCategory === 'makanan_berat' ? 'bg-[#9E460B] border-[#9E460B] text-white shadow-sm' : 'border-[#F4E1D2] text-[#8A7160] hover:bg-white' }}">
                            Makanan Berat
                        </button>
                        <button wire:click="$set('selectedCategory', 'makanan_ringan')" class="px-4 py-2 rounded-full text-xs font-bold border transition shrink-0 {{ $selectedCategory === 'makanan_ringan' ? 'bg-[#9E460B] border-[#9E460B] text-white shadow-sm' : 'border-[#F4E1D2] text-[#8A7160] hover:bg-white' }}">
                            Makanan Ringan
                        </button>
                        <button wire:click="$set('selectedCategory', 'minuman')" class="px-4 py-2 rounded-full text-xs font-bold border transition shrink-0 {{ $selectedCategory === 'minuman' ? 'bg-[#9E460B] border-[#9E460B] text-white shadow-sm' : 'border-[#F4E1D2] text-[#8A7160] hover:bg-white' }}">
                            Minuman
                        </button>
                    </div>

                    <!-- Menu Inventory Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($menus as $menu)
                            <div class="bg-white border border-[#F4E1D2] rounded-[32px] p-6 shadow-sm flex flex-col justify-between space-y-4 hover:shadow-md transition duration-200">
                                <div>
                                    <!-- Photo/Badge -->
                                    <div class="relative w-full h-36 bg-[#FFF8F2] border border-[#F4E1D2] rounded-2xl flex items-center justify-center overflow-hidden mb-4 text-[#9E460B]">
                                        <span class="material-symbols-outlined text-4xl">local_pizza</span>
                                        <!-- Category Tag Badge -->
                                        <span class="absolute top-3 left-3 bg-[#FFF1E5] text-[#9E460B] border border-[#F4E1D2] text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                                            {{ str_replace('_', ' ', $menu->category) }}
                                        </span>
                                        @if($menu->stock <= 0)
                                            <span class="absolute inset-0 bg-black/40 flex items-center justify-center text-white font-extrabold text-xs uppercase tracking-wider">Habis</span>
                                        @elseif($menu->stock <= 5)
                                            <span class="absolute top-3 right-3 bg-red-100 text-red-700 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Stok Menipis</span>
                                        @endif
                                    </div>

                                    <!-- Details -->
                                    <div class="space-y-1">
                                        <div class="flex items-start justify-between gap-2">
                                            <h4 class="font-extrabold text-sm text-[#331C0E] truncate leading-tight font-display">{{ $menu->name }}</h4>
                                            <span class="font-extrabold text-sm text-[#9E460B] shrink-0 font-display">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                                        </div>
                                        <p class="text-[11px] text-[#8A7160] line-clamp-2">{{ $menu->description ?: 'Tidak ada deskripsi.' }}</p>
                                    </div>
                                </div>

                                <div class="space-y-3 pt-2">
                                    <!-- Stock bar -->
                                    <div class="space-y-1.5">
                                        <div class="flex justify-between text-xs font-bold">
                                            <span class="text-[#8A7160]">Stok Saat Ini:</span>
                                            <span class="{{ $menu->stock <= 5 ? 'text-red-650' : 'text-[#331C0E]' }}">{{ $menu->stock }} Porsi</span>
                                        </div>
                                        <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full {{ $menu->stock <= 5 ? 'bg-red-500' : 'bg-[#E27226]' }}" style="width: {{ min(100, $menu->stock * 5) }}%"></div>
                                        </div>
                                    </div>

                                    <!-- Controls -->
                                    <div class="flex items-center justify-between gap-3 pt-1">
                                        <!-- Quick update -->
                                        <div class="flex items-center border border-[#F4E1D2] rounded-xl bg-[#FFFBF7]">
                                            <button wire:click="adjustStock({{ $menu->id }}, -1)" class="w-8 py-2 text-[#8A7160] hover:text-[#9E460B] transition flex items-center justify-center">
                                                <span class="material-symbols-outlined text-sm font-bold">remove</span>
                                            </button>
                                            <span class="w-10 text-center text-xs font-bold text-[#331C0E]">{{ $menu->stock }}</span>
                                            <button wire:click="adjustStock({{ $menu->id }}, 1)" class="w-8 py-2 text-[#8A7160] hover:text-[#9E460B] transition flex items-center justify-center">
                                                <span class="material-symbols-outlined text-sm font-bold">add</span>
                                            </button>
                                        </div>

                                        <!-- Edit/Restock button -->
                                        @if($menu->stock <= 0)
                                            <button wire:click="restock({{ $menu->id }})" class="bg-[#FFF1E5] border border-[#F4E1D2] text-[#9E460B] hover:bg-[#FFF8F2] text-[11px] font-bold px-3 py-2 rounded-xl transition flex items-center gap-1 shadow-sm shrink-0">
                                                <span class="material-symbols-outlined text-sm font-bold">inventory</span>
                                                <span>Isi Ulang</span>
                                            </button>
                                        @else
                                            <button wire:click="openMenuModal({{ $menu->id }})" class="border border-[#F4E1D2] text-[#8A7160] hover:bg-[#FFF8F2] text-[11px] font-bold px-3.5 py-2 rounded-xl transition">
                                                Edit Item
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-24 bg-white border border-dashed border-[#F4E1D2] rounded-[32px] text-[#8A7160]">
                                <span class="material-symbols-outlined text-5xl text-gray-300">search_off</span>
                                <p class="text-sm font-bold mt-2">Tidak ada item menu yang sesuai.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif

            <!-- 3. REPORTS TAB (Mockup Image 4) -->
            @if($activeTab === 'reports')
                <div class="space-y-8">
                    <div>
                        <h2 class="text-2xl font-extrabold text-[#331C0E] font-display">Laporan Transaksi</h2>
                        <p class="text-xs text-[#8A7160] mt-1">Ringkasan kinerja kantin Anda.</p>
                    </div>

                    <!-- Analytics Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Card 1 -->
                        <div class="bg-white border border-[#F4E1D2] rounded-[32px] p-6 shadow-sm space-y-4">
                            <span class="text-xs font-bold text-[#8A7160] uppercase">Pendapatan Hari Ini</span>
                            <div class="flex items-baseline gap-2">
                                <p class="text-3xl font-extrabold text-[#331C0E] font-display">Rp {{ number_format($stats['today_revenue'], 0, ',', '.') }}</p>
                            </div>
                            <span class="text-green-600 text-xs font-bold flex items-center gap-1">
                                <span class="material-symbols-outlined text-xs">arrow_upward</span> +12% vs kemarin
                            </span>
                        </div>
                        <!-- Card 2 -->
                        <div class="bg-white border border-[#F4E1D2] rounded-[32px] p-6 shadow-sm space-y-4">
                            <span class="text-xs font-bold text-[#8A7160] uppercase">Pendapatan Mingguan</span>
                            <div class="flex items-baseline gap-2">
                                <p class="text-3xl font-extrabold text-[#331C0E] font-display">Rp {{ number_format($stats['weekly_revenue'], 0, ',', '.') }}</p>
                            </div>
                            <span class="text-green-600 text-xs font-bold flex items-center gap-1">
                                <span class="material-symbols-outlined text-xs">arrow_upward</span> +5% vs minggu lalu
                            </span>
                        </div>
                        <!-- Card 3 -->
                        <div class="bg-white border border-[#F4E1D2] rounded-[32px] p-6 shadow-sm space-y-4">
                            <span class="text-xs font-bold text-[#8A7160] uppercase">Total Pesanan Hari Ini</span>
                            <div class="flex items-baseline gap-2">
                                <p class="text-3xl font-extrabold text-[#331C0E] font-display">{{ $stats['total_orders_today'] }}</p>
                            </div>
                            <span class="text-red-500 text-xs font-bold flex items-center gap-1">
                                <span class="material-symbols-outlined text-xs">arrow_downward</span> -2% vs kemarin
                            </span>
                        </div>
                    </div>

                    <!-- Volume Chart Section -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2 bg-white border border-[#F4E1D2] rounded-[32px] p-6 shadow-sm space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-extrabold text-[#331C0E] font-display">Volume Pesanan</span>
                                <div class="flex gap-1.5">
                                    <button class="px-3 py-1 bg-gray-50 hover:bg-gray-100 rounded-lg text-xs font-bold text-gray-500">Harian</button>
                                    <button class="px-3 py-1 bg-[#FFF1E5] text-[#9E460B] border border-[#F4E1D2] rounded-lg text-xs font-bold">Mingguan</button>
                                </div>
                            </div>

                            <!-- Interactive Pure CSS Bar Chart -->
                            <div class="h-64 pt-6 flex items-end justify-between gap-3 text-center border-b border-gray-100 pb-2 relative">
                                <div class="absolute inset-0 flex flex-col justify-between pointer-events-none text-[10px] text-gray-300">
                                    <div class="border-b border-dashed border-gray-100 pb-1">300</div>
                                    <div class="border-b border-dashed border-gray-100 pb-1">200</div>
                                    <div class="border-b border-dashed border-gray-100 pb-1">100</div>
                                    <div>0</div>
                                </div>

                                <div class="flex-grow flex items-end justify-around h-full z-10">
                                    <div class="flex flex-col items-center justify-end gap-2 w-10 h-full">
                                        <div class="bg-[#FCD8BD] w-full rounded-t-lg transition hover:bg-[#E27226]" style="height: 40%"></div>
                                        <span class="text-[10px] text-[#8A7160] font-bold">Sen</span>
                                    </div>
                                    <div class="flex flex-col items-center justify-end gap-2 w-10 h-full">
                                        <div class="bg-[#FCD8BD] w-full rounded-t-lg transition hover:bg-[#E27226]" style="height: 60%"></div>
                                        <span class="text-[10px] text-[#8A7160] font-bold">Sel</span>
                                    </div>
                                    <div class="flex flex-col items-center justify-end gap-2 w-10 h-full">
                                        <div class="bg-[#FCD8BD] w-full rounded-t-lg transition hover:bg-[#E27226]" style="height: 35%"></div>
                                        <span class="text-[10px] text-[#8A7160] font-bold">Rab</span>
                                    </div>
                                    <div class="flex flex-col items-center justify-end gap-2 w-10 h-full">
                                        <div class="bg-[#9E460B] w-full rounded-t-lg shadow-sm" style="height: 80%"></div>
                                        <span class="text-[10px] text-[#9E460B] font-bold">Kam</span>
                                    </div>
                                    <div class="flex flex-col items-center justify-end gap-2 w-10 h-full">
                                        <div class="bg-[#FCD8BD] w-full rounded-t-lg transition hover:bg-[#E27226]" style="height: 55%"></div>
                                        <span class="text-[10px] text-[#8A7160] font-bold">Jum</span>
                                    </div>
                                    <div class="flex flex-col items-center justify-end gap-2 w-10 h-full">
                                        <div class="bg-[#FCD8BD] w-full rounded-t-lg transition hover:bg-[#E27226]" style="height: 70%"></div>
                                        <span class="text-[10px] text-[#8A7160] font-bold">Sab</span>
                                    </div>
                                    <div class="flex flex-col items-center justify-end gap-2 w-10 h-full">
                                        <div class="bg-[#FCD8BD] w-full rounded-t-lg transition hover:bg-[#E27226]" style="height: 75%"></div>
                                        <span class="text-[10px] text-[#8A7160] font-bold">Min</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Top Items -->
                        <div class="bg-white border border-[#F4E1D2] rounded-[32px] p-6 shadow-sm space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-extrabold text-[#331C0E] font-display">Item Teratas</span>
                                <button class="text-xs font-bold text-[#9E460B] hover:underline">Lihat Semua</button>
                            </div>

                            <div class="divide-y divide-[#F4E1D2] space-y-3">
                                @forelse($topItems as $item)
                                    <div class="flex items-center justify-between py-2 first:pt-0 last:pb-0">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-[#FFF8F2] border border-[#F4E1D2] flex items-center justify-center text-[#9E460B] shrink-0">
                                                <span class="material-symbols-outlined text-xl">restaurant</span>
                                            </div>
                                            <div>
                                                <h4 class="font-extrabold text-xs text-[#331C0E] truncate max-w-[120px]">{{ $item->menu_name_snapshot }}</h4>
                                                <span class="text-[10px] text-[#8A7160]">{{ $item->total_qty }} pesanan</span>
                                            </div>
                                        </div>
                                        <span class="font-extrabold text-xs text-[#331C0E]">Rp {{ number_format($item->total_subtotal, 0, ',', '.') }}</span>
                                    </div>
                                @empty
                                    <p class="text-xs text-[#8A7160] py-4 text-center">Belum ada pesanan yang selesai.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- 4. PROFILE TAB (Mockup Image 5) -->
            @if($activeTab === 'profile')
                <div class="space-y-8">
                    <div>
                        <h2 class="text-2xl font-extrabold text-[#331C0E] font-display">Profil Penjual</h2>
                        <p class="text-xs text-[#8A7160] mt-1">Kelola detail kantin publik dan informasi kontak Anda.</p>
                    </div>

                    <!-- Profile Form Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Basic details card -->
                            <div class="bg-white border border-[#F4E1D2] rounded-[32px] p-8 shadow-sm space-y-6">
                                <!-- Banner avatar -->
                                <div class="flex items-center gap-6 pb-2">
                                    <div class="w-20 h-20 rounded-full bg-[#FFF8F2] border border-[#F4E1D2] flex items-center justify-center text-[#9E460B] relative">
                                        <span class="material-symbols-outlined text-4xl">storefront</span>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-extrabold text-[#331C0E] leading-tight font-display">{{ $storeName }}</h3>
                                        <span class="bg-[#FFF1E5] text-[#9E460B] border border-[#F4E1D2] text-[10px] font-bold px-2 py-0.5 rounded-full uppercase mt-1 inline-block">Penjual Terverifikasi</span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <!-- Canteen Name -->
                                    <div class="space-y-2 col-span-full">
                                        <label class="text-xs font-bold text-[#8A7160] uppercase">Nama Kantin</label>
                                        <input type="text" wire:model="storeName" class="w-full p-4 bg-[#FFFBF7] border border-[#F4E1D2] rounded-2xl text-xs text-[#331C0E] focus:ring-2 focus:ring-[#E27226]/50 transition">
                                    </div>
                                    <!-- Short Description -->
                                    <div class="space-y-2 col-span-full">
                                        <label class="text-xs font-bold text-[#8A7160] uppercase">Deskripsi Singkat</label>
                                        <textarea wire:model="sellerDescription" class="w-full p-4 bg-[#FFFBF7] border border-[#F4E1D2] rounded-2xl text-xs text-[#331C0E] h-28 resize-none focus:ring-2 focus:ring-[#E27226]/50 transition"></textarea>
                                    </div>
                                </div>

                                <!-- Action button -->
                                <div class="pt-2 flex justify-end">
                                    <button wire:click="updateProfile" class="bg-[#9E460B] hover:bg-[#803708] text-white py-3.5 px-6 rounded-2xl font-bold transition text-xs shadow-sm active:scale-95 duration-150">
                                        Simpan Perubahan Identitas
                                    </button>
                                </div>
                            </div>

                            <!-- Contact Details -->
                            <div class="bg-white border border-[#F4E1D2] rounded-[32px] p-8 shadow-sm space-y-6">
                                <h3 class="text-base font-extrabold text-[#331C0E] font-display">Detail Kontak</h3>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div class="space-y-2 col-span-full">
                                        <label class="text-xs font-bold text-[#8A7160] uppercase">Alamat Email (Publik)</label>
                                        <input type="email" value="{{ auth()->user()->email }}" disabled class="w-full p-4 bg-gray-50 border border-[#F4E1D2] rounded-2xl text-xs text-gray-500 cursor-not-allowed">
                                    </div>
                                    <div class="space-y-2 col-span-full">
                                        <label class="text-xs font-bold text-[#8A7160] uppercase">Nomor Telepon</label>
                                        <input type="text" wire:model="sellerPhone" class="w-full p-4 bg-[#FFFBF7] border border-[#F4E1D2] rounded-2xl text-xs text-[#331C0E] focus:ring-2 focus:ring-[#E27226]/50 transition">
                                    </div>
                                    <div class="space-y-2 col-span-full">
                                        <label class="text-xs font-bold text-[#8A7160] uppercase">Lokasi Kampus</label>
                                        <input type="text" wire:model="sellerLocation" class="w-full p-4 bg-[#FFFBF7] border border-[#F4E1D2] rounded-2xl text-xs text-[#331C0E] focus:ring-2 focus:ring-[#E27226]/50 transition">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Panel -->
                        <div class="space-y-6">
                            <!-- Manager Info -->
                            <div class="bg-white border border-[#F4E1D2] rounded-[32px] p-8 shadow-sm space-y-6">
                                <h3 class="text-base font-extrabold text-[#331C0E] font-display">Info Manajer</h3>
                                
                                <div class="space-y-4">
                                    <div class="space-y-2">
                                        <label class="text-xs font-bold text-[#8A7160] uppercase">Nama Lengkap</label>
                                        <input type="text" wire:model="sellerName" class="w-full p-4 bg-[#FFFBF7] border border-[#F4E1D2] rounded-2xl text-xs text-[#331C0E] focus:ring-2 focus:ring-[#E27226]/50 transition">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-xs font-bold text-[#8A7160] uppercase">Jabatan</label>
                                        <input type="text" wire:model="sellerRole" class="w-full p-4 bg-[#FFFBF7] border border-[#F4E1D2] rounded-2xl text-xs text-[#331C0E] focus:ring-2 focus:ring-[#E27226]/50 transition">
                                    </div>
                                </div>
                            </div>

                            <!-- Preferences -->
                            <div class="bg-white border border-[#F4E1D2] rounded-[32px] p-8 shadow-sm space-y-6">
                                <h3 class="text-base font-extrabold text-[#331C0E] font-display">Preferensi</h3>
                                
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-xs font-bold text-[#331C0E]">Notifikasi Pesanan Baru</h4>
                                            <p class="text-[10px] text-[#8A7160]">Terima notifikasi untuk pesanan masuk.</p>
                                        </div>
                                        <button wire:click="$toggle('alertNewOrder')" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out {{ $alertNewOrder ? 'bg-[#E27226]' : 'bg-gray-200' }}">
                                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $alertNewOrder ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                        </button>
                                    </div>
                                    <div class="flex items-center justify-between border-t border-[#F4E1D2] pt-4">
                                        <div>
                                            <h4 class="text-xs font-bold text-[#331C0E]">Peringatan Stok Menipis</h4>
                                            <p class="text-[10px] text-[#8A7160]">Ringkasan email harian untuk item stok menipis.</p>
                                        </div>
                                        <button wire:click="$toggle('alertLowStock')" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out {{ $alertLowStock ? 'bg-[#E27226]' : 'bg-gray-200' }}">
                                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $alertLowStock ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </main>
    </div>

    <!-- Menu Modal (Create & Edit dialog) -->
    <div x-show="openMenuModal" 
         class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4"
         x-cloak>
        <div class="bg-white rounded-[32px] p-8 max-w-md w-full border border-[#F4E1D2] shadow-2xl space-y-6 animate-in fade-in zoom-in-95 duration-200"
             @click.away="openMenuModal = false">
            <div class="text-center space-y-1">
                <h3 class="text-xl font-extrabold text-[#331C0E] font-display">{{ $editingMenuId ? 'Edit Item Menu' : 'Tambah Item Menu Baru' }}</h3>
                <p class="text-xs text-[#8A7160]">Tentukan detail untuk daftar menu kantin Anda.</p>
            </div>

            <form wire:submit.prevent="saveMenu" class="space-y-4 text-xs">
                <!-- Name -->
                <div class="space-y-1.5">
                    <label class="font-bold text-[#8A7160] uppercase">Nama Item</label>
                    <input type="text" wire:model="menuName" placeholder="cth. Nasi Goreng" 
                           class="w-full p-3.5 bg-[#FFFBF7] border border-[#F4E1D2] rounded-xl text-xs text-[#331C0E] focus:ring-2 focus:ring-[#E27226]/50 transition">
                    @error('menuName') <span class="text-red-500 font-bold text-[10px]">{{ $message }}</span> @enderror
                </div>

                <!-- Description -->
                <div class="space-y-1.5">
                    <label class="font-bold text-[#8A7160] uppercase">Deskripsi</label>
                    <textarea wire:model="menuDescription" placeholder="Tulis deskripsi, komposisi, tag..." 
                              class="w-full p-3.5 bg-[#FFFBF7] border border-[#F4E1D2] rounded-xl text-xs text-[#331C0E] h-20 resize-none focus:ring-2 focus:ring-[#E27226]/50 transition"></textarea>
                    @error('menuDescription') <span class="text-red-500 font-bold text-[10px]">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Category -->
                    <div class="space-y-1.5">
                        <label class="font-bold text-[#8A7160] uppercase">Kategori</label>
                        <select wire:model="menuCategory" 
                                class="w-full p-3.5 bg-[#FFFBF7] border border-[#F4E1D2] rounded-xl text-xs text-[#331C0E] focus:ring-2 focus:ring-[#E27226]/50 transition">
                            <option value="makanan_berat">Makanan Berat</option>
                            <option value="makanan_ringan">Makanan Ringan</option>
                            <option value="minuman">Minuman</option>
                        </select>
                        @error('menuCategory') <span class="text-red-500 font-bold text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <!-- Cooking Time -->
                    <div class="space-y-1.5">
                        <label class="font-bold text-[#8A7160] uppercase">Waktu Persiapan (mnt)</label>
                        <input type="number" wire:model="menuCookingTime" 
                               class="w-full p-3.5 bg-[#FFFBF7] border border-[#F4E1D2] rounded-xl text-xs text-[#331C0E] focus:ring-2 focus:ring-[#E27226]/50 transition">
                        @error('menuCookingTime') <span class="text-red-500 font-bold text-[10px]">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Price -->
                    <div class="space-y-1.5">
                        <label class="font-bold text-[#8A7160] uppercase">Harga (Rp)</label>
                        <input type="number" wire:model="menuPrice" 
                               class="w-full p-3.5 bg-[#FFFBF7] border border-[#F4E1D2] rounded-xl text-xs text-[#331C0E] focus:ring-2 focus:ring-[#E27226]/50 transition">
                        @error('menuPrice') <span class="text-red-500 font-bold text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <!-- Stock -->
                    <div class="space-y-1.5">
                        <label class="font-bold text-[#8A7160] uppercase">Stok Awal</label>
                        <input type="number" wire:model="menuStock" 
                               class="w-full p-3.5 bg-[#FFFBF7] border border-[#F4E1D2] rounded-xl text-xs text-[#331C0E] focus:ring-2 focus:ring-[#E27226]/50 transition">
                        @error('menuStock') <span class="text-red-500 font-bold text-[10px]">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="space-y-2.5 pt-4">
                    <button type="submit" 
                            class="w-full bg-[#E27226] hover:bg-[#c95d12] text-white py-4 rounded-2xl font-bold transition duration-150 shadow-md">
                        {{ $editingMenuId ? 'Simpan Item Menu' : 'Buat Item Menu' }}
                    </button>
                    @if($editingMenuId)
                        <button type="button" wire:click="deleteMenu({{ $editingMenuId }})" 
                                class="w-full border border-red-200 text-red-500 hover:bg-red-50 py-4 rounded-2xl font-bold transition duration-150">
                            Hapus Item Menu
                        </button>
                    @endif
                    <button type="button" @click="openMenuModal = false" 
                            class="w-full border border-gray-200 text-gray-500 hover:bg-gray-50 py-4 rounded-2xl font-bold transition duration-150">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
