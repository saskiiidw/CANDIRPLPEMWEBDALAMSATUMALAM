<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SmartCanteen') }} — Admin</title>

    <!-- Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#FFFBF7] text-[#331C0E]">
<div class="flex min-h-screen">

    <!-- ── Sidebar ─────────────────────────────────────────────────── -->
    <aside class="w-64 bg-[#FFF1E5] border-r border-[#F4E1D2] flex flex-col justify-between flex-shrink-0 sticky top-0 h-screen">
        <div class="p-6 space-y-6">

            <!-- Logo / Brand (Image 1 style) -->
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-white border border-[#F4E1D2] flex items-center justify-center shadow-sm shrink-0">
                    <span class="material-symbols-outlined text-[#E27226] text-xl">description</span>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-[#7c3300] leading-tight">Admin Panel</h2>
                    <p class="text-[10px] text-[#8A7160]">Management Suite</p>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[#E27226] text-white shadow-sm' : 'text-[#8A7160] hover:bg-[#FFF8F2] hover:text-[#9E460B]' }}">
                    <span class="material-symbols-outlined text-lg">dashboard</span>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.seller-verification') }}"
                   class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.seller-verification') ? 'bg-[#E27226] text-white shadow-sm' : 'text-[#8A7160] hover:bg-[#FFF8F2] hover:text-[#9E460B]' }}">
                    <span class="material-symbols-outlined text-lg">verified_user</span>
                    <span>Seller Verification</span>
                    @php $pendingCount = \App\Models\User::where('role','penjual')->where('is_verified',false)->count(); @endphp
                    @if($pendingCount > 0)
                        <span class="ml-auto bg-[#E27226] text-white text-[9px] font-extrabold px-1.5 py-0.5 rounded-full">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.user-management') }}"
                   class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.user-management') ? 'bg-[#E27226] text-white shadow-sm' : 'text-[#8A7160] hover:bg-[#FFF8F2] hover:text-[#9E460B]' }}">
                    <span class="material-symbols-outlined text-lg">manage_accounts</span>
                    <span>User Management</span>
                </a>
                <a href="{{ route('admin.audit-log') }}"
                   class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.audit-log') ? 'bg-[#E27226] text-white shadow-sm' : 'text-[#8A7160] hover:bg-[#FFF8F2] hover:text-[#9E460B]' }}">
                    <span class="material-symbols-outlined text-lg">history</span>
                    <span>Audit Log</span>
                </a>
                <a href="{{ route('admin.profile') }}"
                   class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.profile') ? 'bg-[#E27226] text-white shadow-sm' : 'text-[#8A7160] hover:bg-[#FFF8F2] hover:text-[#9E460B]' }}">
                    <span class="material-symbols-outlined text-lg">person</span>
                    <span>Profile</span>
                </a>
            </nav>
        </div>

        <!-- Sidebar Footer -->
        <div class="p-6 border-t border-[#F4E1D2] space-y-1">
            <div class="flex items-center gap-3 px-2 mb-3">
                <img class="w-9 h-9 rounded-full border border-[#F4E1D2] object-cover bg-orange-100"
                     src="{{ auth()->user()->photo_path ? asset('storage/'.auth()->user()->photo_path) : 'https://api.dicebear.com/7.x/adventurer/svg?seed='.auth()->user()->name }}"
                     alt="Admin Avatar">
                <div class="min-w-0">
                    <p class="text-xs font-extrabold text-[#331C0E] truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-[#8A7160] truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold text-[#8A7160] hover:bg-red-50 hover:text-red-600 transition-all">
                    <span class="material-symbols-outlined text-lg">logout</span>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- ── Main Content ──────────────────────────────────────────── -->
    <div class="flex-1 flex flex-col min-h-screen">

        <!-- Top Navbar (Clean borderless style) -->
        <header class="h-20 bg-[#FFFBF7] px-8 flex items-center justify-between sticky top-0 z-20 flex-shrink-0">
            <!-- Left: dynamic content injected per page -->
            <div>@stack('header-left')</div>

            <!-- Right Toolbar -->
            <div class="flex items-center gap-4" x-data="{ notifOpen: false }" @click.away="notifOpen = false">

                <!-- Notification Bell -->
                <div class="relative">
                    <button @click="notifOpen = !notifOpen"
                            class="relative p-2 text-[#8A7160] hover:bg-[#FFF1E5] rounded-xl transition">
                        <span class="material-symbols-outlined text-xl">notifications</span>
                        @php $pendingNotif = \App\Models\User::where('role','penjual')->where('is_verified',false)->count(); @endphp
                        @if($pendingNotif > 0)
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-[#E27226] rounded-full border border-[#FFFBF7]"></span>
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
                         class="absolute right-0 mt-2 w-80 bg-white border border-[#F4E1D2] rounded-2xl shadow-xl py-2 z-30"
                         x-cloak>
                        <div class="px-4 py-3 border-b border-[#F4E1D2]">
                            <p class="text-xs font-extrabold text-[#331C0E]">Notifications</p>
                        </div>
                        @php
                            $pendingSellers = \App\Models\User::where('role','penjual')->where('is_verified',false)->latest()->take(5)->get();
                            $recentOrders  = \App\Models\Order::whereIn('status',['ditolak','dibatalkan'])->whereDate('created_at',today())->latest()->take(3)->get();
                        @endphp
                        <div class="max-h-64 overflow-y-auto">
                            @forelse($pendingSellers as $s)
                                <a href="{{ route('admin.seller-verification') }}"
                                   class="flex items-start gap-3 px-4 py-3 hover:bg-[#FFF8F2] transition">
                                    <span class="material-symbols-outlined text-[#E27226] text-lg shrink-0 mt-0.5">pending_actions</span>
                                    <div>
                                        <p class="text-xs font-bold text-[#331C0E]">Seller pending: {{ $s->store_name ?? $s->name }}</p>
                                        <p class="text-[10px] text-[#8A7160]">Awaiting verification · {{ $s->created_at->diffForHumans() }}</p>
                                    </div>
                                </a>
                            @empty
                                <div class="px-4 py-4 text-center text-[#8A7160]">
                                    <span class="material-symbols-outlined text-3xl text-gray-300">notifications_none</span>
                                    <p class="text-xs font-bold mt-1">No new notifications</p>
                                </div>
                            @endforelse
                            @foreach($recentOrders as $ro)
                                <div class="flex items-start gap-3 px-4 py-3 hover:bg-[#FFF8F2] transition">
                                    <span class="material-symbols-outlined text-red-500 text-lg shrink-0 mt-0.5">cancel</span>
                                    <div>
                                        <p class="text-xs font-bold text-[#331C0E]">Order #{{ $ro->id }} {{ $ro->status }}</p>
                                        <p class="text-[10px] text-[#8A7160]">{{ $ro->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="border-t border-[#F4E1D2] px-4 py-2">
                            <a href="{{ route('admin.audit-log') }}" class="text-xs font-bold text-[#9E460B] hover:underline">View all activity →</a>
                        </div>
                    </div>
                </div>

                <!-- Settings -->
                <a href="{{ route('admin.profile') }}" class="p-2 text-[#8A7160] hover:bg-[#FFF1E5] rounded-xl transition">
                    <span class="material-symbols-outlined text-xl">settings</span>
                </a>

                <!-- Avatar + Dropdown -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open"
                            class="w-9 h-9 rounded-full border border-[#F4E1D2] overflow-hidden hover:ring-2 hover:ring-[#E27226]/40 transition">
                        <img src="{{ auth()->user()->photo_path ? asset('storage/'.auth()->user()->photo_path) : 'https://api.dicebear.com/7.x/adventurer/svg?seed='.auth()->user()->name }}"
                             alt="Avatar" class="w-full h-full object-cover">
                    </button>
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white border border-[#F4E1D2] rounded-2xl shadow-xl py-2 z-30"
                         x-cloak>
                        <div class="px-4 py-2 border-b border-[#F4E1D2] mb-1">
                            <p class="text-xs font-extrabold text-[#331C0E] truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-[#8A7160]">Administrator</p>
                        </div>
                        <a href="{{ route('admin.profile') }}"
                           class="flex items-center gap-2 px-4 py-2.5 text-xs text-[#8A7160] hover:bg-[#FFF8F2] hover:text-[#9E460B] font-bold transition">
                            <span class="material-symbols-outlined text-sm">person</span>
                            <span>Profile</span>
                        </a>
                        <div class="border-t border-[#F4E1D2] my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-2 px-4 py-2.5 text-xs text-red-600 hover:bg-red-50 font-bold transition">
                                <span class="material-symbols-outlined text-sm">logout</span>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-8 overflow-y-auto">
            {{ $slot }}
        </main>
    </div>
</div>
</body>
</html>
