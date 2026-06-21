<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Smart Canteen Admin') }}</title>

    <!-- Google Fonts: Plus Jakarta Sans & Work Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-[#231914] bg-[#fff8f6]">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-72 bg-[#fff1eb] border-r border-[#f2dfd5] flex flex-col justify-between shrink-0">
            <div class="p-6">
                <!-- Logo Header -->
                <div class="flex items-center space-x-3 mb-10">
                    <div class="w-10 h-10 rounded-full bg-[#f2dfd5] flex items-center justify-center text-[#9b4500] font-bold">
                        <!-- Custom Logo Icon (Document/List style) -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-headline-md text-lg font-bold text-[#8e4e14] leading-tight">Admin Panel</h2>
                        <p class="font-label-md text-xs text-[#897266]">Management Suite</p>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="space-y-1.5">
                    <!-- Dashboard Link -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-4 py-3 rounded-xl transition duration-150 group font-label-lg {{ request()->routeIs('admin.dashboard') ? 'bg-[#f49b65] text-[#331200]' : 'text-[#897266] hover:bg-[#feeae0] hover:text-[#9b4500]' }}">
                        <svg class="w-5 h-5 mr-3 transition group-hover:scale-110 {{ request()->routeIs('admin.dashboard') ? 'text-[#331200]' : 'text-[#897266]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        Dashboard
                    </a>

                    <!-- Seller Verification -->
                    <a href="{{ route('admin.seller-verification') }}" 
                       class="flex items-center px-4 py-3 rounded-xl transition duration-150 group font-label-lg {{ request()->routeIs('admin.seller-verification') ? 'bg-[#f49b65] text-[#331200]' : 'text-[#897266] hover:bg-[#feeae0] hover:text-[#9b4500]' }}">
                        <svg class="w-5 h-5 mr-3 transition group-hover:scale-110 {{ request()->routeIs('admin.seller-verification') ? 'text-[#331200]' : 'text-[#897266]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        Seller Verification
                    </a>

                    <!-- User Management -->
                    <a href="{{ route('admin.user-management') }}" 
                       class="flex items-center px-4 py-3 rounded-xl transition duration-150 group font-label-lg {{ request()->routeIs('admin.user-management') ? 'bg-[#f49b65] text-[#331200]' : 'text-[#897266] hover:bg-[#feeae0] hover:text-[#9b4500]' }}">
                        <svg class="w-5 h-5 mr-3 transition group-hover:scale-110 {{ request()->routeIs('admin.user-management') ? 'text-[#331200]' : 'text-[#897266]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        User Management
                    </a>

                    <!-- Audit Log -->
                    <a href="{{ route('admin.audit-log') }}" 
                       class="flex items-center px-4 py-3 rounded-xl transition duration-150 group font-label-lg {{ request()->routeIs('admin.audit-log') ? 'bg-[#f49b65] text-[#331200]' : 'text-[#897266] hover:bg-[#feeae0] hover:text-[#9b4500]' }}">
                        <svg class="w-5 h-5 mr-3 transition group-hover:scale-110 {{ request()->routeIs('admin.audit-log') ? 'text-[#331200]' : 'text-[#897266]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Audit Log
                    </a>

                    <!-- Profile -->
                    <a href="{{ route('admin.profile') }}" 
                       class="flex items-center px-4 py-3 rounded-xl transition duration-150 group font-label-lg {{ request()->routeIs('admin.profile') ? 'bg-[#f49b65] text-[#331200]' : 'text-[#897266] hover:bg-[#feeae0] hover:text-[#9b4500]' }}">
                        <svg class="w-5 h-5 mr-3 transition group-hover:scale-110 {{ request()->routeIs('admin.profile') ? 'text-[#331200]' : 'text-[#897266]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Profile
                    </a>
                </nav>
            </div>

            <!-- Sidebar Footer / User Quick Profile -->
            <div class="p-6 border-t border-[#f2dfd5] flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <img class="w-10 h-10 rounded-full border border-[#f2dfd5]" 
                         src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80" 
                         alt="Admin Avatar">
                    <div>
                        <h4 class="text-sm font-semibold text-[#331200]">Eleanor Vance</h4>
                        <p class="text-xs text-[#897266]">Super Admin</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-h-screen">
            <!-- Top Navbar -->
            <header class="h-20 bg-[#fff8f6] px-10 flex items-center justify-between shrink-0">
                <!-- Dynamic or Blank Left (handled by page content) -->
                <div>
                    @stack('header-left')
                </div>

                <!-- Right Toolbar -->
                <div class="flex items-center space-x-6">
                    <!-- Notifications -->
                    <button class="relative p-2 text-[#8e4e14] hover:bg-[#fff1eb] rounded-full transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    <!-- Settings -->
                    <a href="{{ route('admin.profile') }}" class="p-2 text-[#8e4e14] hover:bg-[#fff1eb] rounded-full transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </a>

                    <!-- User Profile Avatar -->
                    <img class="w-9 h-9 rounded-full object-cover border border-[#f2dfd5]" 
                         src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80" 
                         alt="Admin Avatar">
                </div>
            </header>

            <!-- Main Page Panel -->
            <main class="flex-1 p-10 pt-4 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
