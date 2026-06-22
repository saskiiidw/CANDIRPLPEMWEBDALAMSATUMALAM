<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SmartCanteen') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Work+Sans:wght@400;500;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#FAF6F2] text-[#231914]">
        <div class="min-h-screen">
            @auth
                @if(auth()->user()->role === 'mahasiswa')
                    <!-- ── Student Layout ──────────────────────────────── -->
                    <div class="flex min-h-screen bg-[#FAF6F2]">

                        <!-- Sidebar -->
                        <aside class="hidden md:flex flex-col w-64 bg-[#FAF3EB] border-r border-[#f2dfd5] p-6 justify-between flex-shrink-0 sticky top-0 h-screen">
                            <div class="space-y-6">
                                <!-- Logo -->
                                    SmartCanteen
                                </a>

                                <!-- User Card -->
                                <div class="flex items-center gap-3">
                                    <img src="{{ auth()->user()->photo_path ? asset('storage/'.auth()->user()->photo_path) : 'https://api.dicebear.com/7.x/adventurer/svg?seed='.auth()->user()->name }}"
                                         alt="Profile photo"
                                         class="w-11 h-11 rounded-full border-2 border-[#ffab69]/40 object-cover bg-orange-100 shadow-sm">
                                    <div class="min-w-0">
                                        <h4 class="font-extrabold text-xs text-[#231914] leading-tight truncate">{{ auth()->user()->name }}</h4>
                                        <p class="text-[10px] text-[#897266] mt-0.5">Siap untuk makan? 🍽</p>
                                    </div>
                                </div>

                                @php
                                    $firstCanteen = \App\Models\User::where('role', 'penjual')->where('is_verified', true)->where('is_active', true)->first();
                                    $myOrdersUrl  = $firstCanteen ? route('canteen.order', ['seller' => $firstCanteen->id]) : '#';
                                @endphp

                                <!-- Quick Order CTA -->
                                <a href="{{ $myOrdersUrl }}" class="flex items-center justify-center gap-2 w-full py-3 bg-[#8c3b03] hover:bg-[#a64605] text-white text-xs font-bold rounded-2xl transition-all shadow-md active:scale-95">
                                    <span class="material-symbols-outlined text-base">bolt</span>
                                    Pesan Cepat
                                </a>

                                <!-- Nav List -->
                                <nav class="space-y-1">
                                    <a href="{{ route('home.student') }}"
                                       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('home.student') ? 'bg-[#fca366] text-[#4d1f00] shadow-sm' : 'text-[#897266] hover:bg-[#ffab69]/10 hover:text-[#9b4500]' }}">
                                        <span class="material-symbols-outlined text-lg">home</span>
                                        Beranda
                                    </a>
                                    <a href="{{ $myOrdersUrl }}"
                                       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('canteen.order') ? 'bg-[#fca366] text-[#4d1f00] shadow-sm' : 'text-[#897266] hover:bg-[#ffab69]/10 hover:text-[#9b4500]' }}">
                                        <span class="material-symbols-outlined text-lg">shopping_bag</span>
                                        Pesanan Saya
                                    </a>
                                    <a href="{{ route('orders.history') }}"
                                       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('orders.history') ? 'bg-[#fca366] text-[#4d1f00] shadow-sm' : 'text-[#897266] hover:bg-[#ffab69]/10 hover:text-[#9b4500]' }}">
                                        <span class="material-symbols-outlined text-lg">history</span>
                                        Riwayat
                                    </a>
                                    <a href="{{ route('profile') }}"
                                       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs(['profile', 'profile.edit']) ? 'bg-[#fca366] text-[#4d1f00] shadow-sm' : 'text-[#897266] hover:bg-[#ffab69]/10 hover:text-[#9b4500]' }}">
                                        <span class="material-symbols-outlined text-lg">person</span>
                                        Profil
                                    </a>
                                </nav>
                            </div>

                            <!-- Sidebar footer -->
                            <div class="space-y-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold text-[#897266] hover:bg-red-50 hover:text-red-600 transition-all">
                                        <span class="material-symbols-outlined text-lg">logout</span>
                                        Keluar
                                    </button>
                                </form>
                                <div class="text-[10px] text-[#897266]/40 text-center font-medium pt-2">© SmartCanteen v1.0</div>
                            </div>
                        </aside>

                        <!-- Main Content Pane -->
                        <div class="flex-1 flex flex-col min-w-0 min-h-screen">

                            <!-- ── Top Navigation (always visible for students) ── -->
                            <header class="h-16 bg-[#FAF3EB] border-b border-[#f2dfd5] px-6 flex items-center justify-between sticky top-0 z-20 flex-shrink-0"
                                    x-data="{ notifOpen: false }" @click.away="notifOpen = false">

                                <style>
                                    @media (min-width: 768px) {
                                        .desktop-hide {
                                            display: none !important;
                                        }
                                        .desktop-dropdown {
                                            position: absolute !important;
                                            right: 0 !important;
                                            left: auto !important;
                                        }
                                    }
                                </style>

                                <!-- Mobile: hamburger placeholder + branding -->
                                <div class="flex items-center gap-4 desktop-hide">
                                    <span class="text-lg font-extrabold text-[#7c3300]">SmartCanteen</span>
                                </div>

                                <!-- Desktop: Breadcrumb / Page Title -->
                                <div class="hidden md:flex items-center gap-2 text-xs text-[#897266]">
                                    <span class="material-symbols-outlined text-base">restaurant</span>
                                    <span class="font-bold text-[#231914]">
                                        @if(request()->routeIs('home.student'))    Beranda
                                        @elseif(request()->routeIs('canteen.order'))  Pesan
                                        @elseif(request()->routeIs('orders.history')) Riwayat
                                        @elseif(request()->routeIs('orders.track'))   Lacak Pesanan
                                        @elseif(request()->routeIs(['profile','profile.edit'])) Profil
                                        @else SmartCanteen
                                        @endif
                                    </span>
                                </div>

                                <!-- Right actions -->
                                <div class="flex items-center gap-3">
                                    <!-- Cart -->
                                    @php $cartItems = session('cart', []); $cartCount = array_sum($cartItems); @endphp
                                    <a href="{{ $myOrdersUrl }}" class="relative p-2 text-[#897266] hover:text-[#9b4500] hover:bg-[#ffab69]/10 rounded-xl transition-all">
                                        <span class="material-symbols-outlined text-xl">shopping_cart</span>
                                        @if($cartCount > 0)
                                            <span class="absolute top-1 right-1 w-4 h-4 bg-[#9b4500] text-white text-[9px] font-extrabold flex items-center justify-center rounded-full">{{ $cartCount }}</span>
                                        @endif
                                    </a>

                                    <!-- Notifications -->
                                    <div class="relative" style="position: relative;">
                                        <button @click="notifOpen = !notifOpen"
                                                class="relative p-2 text-[#897266] hover:text-[#9b4500] hover:bg-[#ffab69]/10 rounded-xl transition-all">
                                            <span class="material-symbols-outlined text-xl">notifications</span>
                                            @php
                                                $activeOrders = \App\Models\Order::where('buyer_id', auth()->id())
                                                    ->whereIn('status', ['diterima','diproses','siap_diambil'])
                                                    ->count();
                                            @endphp
                                            @if($activeOrders > 0)
                                                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-[#9b4500] rounded-full border border-[#FAF3EB]"></span>
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
                                             class="absolute right-0 top-full mt-2 w-80 bg-white border border-[#feeae0] rounded-2xl shadow-xl py-2 z-30 desktop-dropdown"
                                             x-cloak>
                                            <div class="px-4 py-3 border-b border-[#feeae0]">
                                                <p class="text-xs font-extrabold text-[#231914]">Notifikasi</p>
                                            </div>
                                            <div class="max-h-64 overflow-y-auto">
                                                @php
                                                    $myOrders = \App\Models\Order::with('seller')
                                                        ->where('buyer_id', auth()->id())
                                                        ->whereIn('status', ['diterima','diproses','siap_diambil'])
                                                        ->latest()->take(5)->get();
                                                @endphp
                                                @forelse($myOrders as $ord)
                                                    <a href="{{ route('orders.track', $ord->id) }}"
                                                       class="flex items-start gap-3 px-4 py-3 hover:bg-[#FFF8F2] transition">
                                                        <span class="material-symbols-outlined text-lg shrink-0 mt-0.5 {{ $ord->status === 'siap_diambil' ? 'text-green-500' : ($ord->status === 'diproses' ? 'text-blue-500' : 'text-[#E27226]') }}">
                                                            {{ $ord->status === 'siap_diambil' ? 'check_circle' : ($ord->status === 'diproses' ? 'soup_kitchen' : 'receipt_long') }}
                                                        </span>
                                                        <div>
                                                            <p class="text-xs font-bold text-[#231914]">Pesanan #{{ $ord->id }} — {{ ucfirst(str_replace('_',' ',$ord->status)) }}</p>
                                                            <p class="text-[10px] text-[#897266]">{{ $ord->seller->store_name ?? 'Kantin' }} · {{ $ord->created_at->diffForHumans() }}</p>
                                                        </div>
                                                    </a>
                                                @empty
                                                    <div class="px-4 py-6 text-center text-[#897266]">
                                                        <span class="material-symbols-outlined text-3xl text-gray-300">notifications_none</span>
                                                        <p class="text-xs font-bold mt-1">Tidak ada pesanan aktif</p>
                                                    </div>
                                                @endforelse
                                            </div>
                                            <div class="border-t border-[#feeae0] px-4 py-2">
                                                <a href="{{ route('orders.history') }}" class="text-xs font-bold text-[#9b4500] hover:underline">Lihat semua pesanan →</a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Avatar + Dropdown -->
                                    <div class="relative" style="position: relative;" x-data="{ open: false }" @click.away="open = false">
                                        <button @click="open = !open" class="p-0.5 rounded-full border border-[#ffab69]/40 hover:opacity-80 transition-all">
                                            <img src="{{ auth()->user()->photo_path ? asset('storage/'.auth()->user()->photo_path) : 'https://api.dicebear.com/7.x/adventurer/svg?seed='.auth()->user()->name }}"
                                                 alt="Avatar" class="w-8 h-8 rounded-full object-cover bg-orange-100">
                                        </button>
                                        <div x-show="open"
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="opacity-0 scale-95"
                                             x-transition:enter-end="opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="opacity-100 scale-100"
                                             x-transition:leave-end="opacity-0 scale-95"
                                             class="absolute right-0 top-full mt-2 w-48 bg-white border border-[#feeae0] rounded-2xl shadow-xl py-2 z-30 desktop-dropdown"
                                             x-cloak>
                                            <div class="px-4 py-2 border-b border-[#feeae0] mb-1">
                                                <p class="text-xs font-extrabold text-[#231914] truncate">{{ auth()->user()->name }}</p>
                                                <p class="text-[10px] text-[#897266]">Mahasiswa</p>
                                            </div>
                                            <a href="{{ route('profile') }}"
                                               class="flex items-center gap-2 px-4 py-2.5 text-xs text-[#897266] hover:bg-[#FFF8F2] hover:text-[#9b4500] font-bold transition">
                                                <span class="material-symbols-outlined text-sm">person</span> Profil
                                            </a>
                                            <div class="border-t border-[#feeae0] my-1"></div>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-xs text-red-600 hover:bg-red-50 font-bold transition">
                                                    <span class="material-symbols-outlined text-sm">logout</span> Keluar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </header>

                            <!-- Content Slot -->
                            <main class="flex-1 overflow-y-auto p-6 md:p-8">
                                {{ $slot }}
                            </main>
                        </div>
                    </div>

                @else
                    <!-- Penjual / Admin: sidebar managed inside their own view -->
                    <div class="min-h-screen bg-[#FFFBF7]">
                        {{ $slot }}
                    </div>
                @endif

            @else
                <!-- Guest Layout -->
                <div class="min-h-screen bg-[#FFFBF7] flex flex-col justify-center items-center">
                    {{ $slot }}
                </div>
            @endauth
        </div>
    </body>
</html>
