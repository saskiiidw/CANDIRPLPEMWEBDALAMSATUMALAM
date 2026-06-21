<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CampusBites') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Work+Sans:wght@400;500;600&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#fff8f6] text-[#231914]">
        <div class="min-h-screen">
            @auth
                @if(auth()->user()->role === 'mahasiswa')
                    <!-- Student Layout Wrapper -->
                    <div class="flex min-h-screen">
                        <!-- Sidebar Navigation -->
                        <aside class="hidden md:flex flex-col w-64 bg-[#FAF3EB] border-r border-[#f2dfd5] p-6 justify-between flex-shrink-0">
                            <div class="space-y-8">
                                <!-- Logo -->
                                <div>
                                    <a href="{{ route('home.student') }}" class="text-3xl font-extrabold text-[#9b4500] tracking-tight font-headline-lg">
                                        CampusBites
                                    </a>
                                </div>

                                <!-- User Profile Card -->
                                <div class="flex items-center gap-3 bg-[#fff8f6] p-4 rounded-2xl border border-[#feeae0]">
                                    <img src="{{ auth()->user()->photo_path ? asset('storage/' . auth()->user()->photo_path) : 'https://api.dicebear.com/7.x/adventurer/svg?seed=' . auth()->user()->name }}" 
                                         alt="Profile photo" 
                                         class="w-12 h-12 rounded-full border-2 border-[#ffab69] object-cover bg-orange-100" />
                                    <div>
                                        <h4 class="font-bold text-sm text-[#231914]">Welcome, {{ explode(' ', auth()->user()->name)[0] }}</h4>
                                        <p class="text-xs text-[#897266]">Ready for a meal?</p>
                                    </div>
                                </div>

                                <!-- Nav List -->
                                <nav class="space-y-2">
                                    <a href="{{ route('home.student') }}" 
                                       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold transition-all {{ request()->routeIs('home.student') ? 'bg-[#ffab69]/30 text-[#9b4500] shadow-sm' : 'text-[#897266] hover:bg-[#ffab69]/10 hover:text-[#9b4500]' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                        Home
                                    </a>
                                    
                                    @php
                                        // Find first canteen for Quick Order/My Orders link fallback if needed
                                        $firstCanteen = \App\Models\User::where('role', 'penjual')->where('is_active', true)->first();
                                        $myOrdersUrl = $firstCanteen ? route('canteen.order', ['seller' => $firstCanteen->id]) : '#';
                                    @endphp

                                    <a href="{{ $myOrdersUrl }}" 
                                       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold transition-all {{ request()->routeIs('canteen.order') ? 'bg-[#ffab69]/30 text-[#9b4500] shadow-sm' : 'text-[#897266] hover:bg-[#ffab69]/10 hover:text-[#9b4500]' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                        My Orders
                                    </a>
                                    
                                    <a href="{{ route('orders.history') }}" 
                                       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold transition-all {{ request()->routeIs('orders.history') ? 'bg-[#ffab69]/30 text-[#9b4500] shadow-sm' : 'text-[#897266] hover:bg-[#ffab69]/10 hover:text-[#9b4500]' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        History
                                    </a>
                                    
                                    <a href="{{ route('profile') }}" 
                                       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold transition-all {{ request()->routeIs('profile') ? 'bg-[#ffab69]/30 text-[#9b4500] shadow-sm' : 'text-[#897266] hover:bg-[#ffab69]/10 hover:text-[#9b4500]' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                        Profile
                                    </a>
                                </nav>
                            </div>

                            <!-- Sidebar Quick Order Button -->
                            <div>
                                <a href="{{ $myOrdersUrl }}" class="flex items-center justify-center w-full py-4 bg-[#8e4e14] hover:bg-[#9b4500] text-white text-sm font-extrabold rounded-2xl transition-all shadow-md active:scale-95">
                                    Quick Order
                                </a>
                            </div>
                        </aside>

                        <!-- Main Content Pane -->
                        <div class="flex-1 flex flex-col min-w-0 min-h-screen">
                            
                            <!-- Top Navigation / Header -->
                            @if(request()->routeIs(['home.student', 'orders.history', 'profile']))
                                <header class="h-20 bg-[#fff8f6] px-8 flex items-center justify-between border-b border-[#feeae0] flex-shrink-0">
                                    <!-- Horizontal Navigation Links -->
                                    <div class="flex items-center gap-8">
                                        <a href="{{ route('home.student') }}" 
                                           class="relative py-2 text-sm font-bold transition-colors font-label-lg {{ request()->routeIs('home.student') ? 'text-[#9b4500]' : 'text-[#897266] hover:text-[#9b4500]' }}">
                                            Home
                                            @if(request()->routeIs('home.student'))
                                                <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-[#9b4500] rounded-full"></span>
                                            @endif
                                        </a>
                                        <a href="{{ $myOrdersUrl }}" 
                                           class="relative py-2 text-sm font-bold transition-colors font-label-lg {{ request()->routeIs('canteen.order') ? 'text-[#9b4500]' : 'text-[#897266] hover:text-[#9b4500]' }}">
                                            My Orders
                                        </a>
                                        <a href="{{ route('orders.history') }}" 
                                           class="relative py-2 text-sm font-bold transition-colors font-label-lg {{ request()->routeIs('orders.history') ? 'text-[#9b4500]' : 'text-[#897266] hover:text-[#9b4500]' }}">
                                            History
                                            @if(request()->routeIs('orders.history'))
                                                <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-[#9b4500] rounded-full"></span>
                                            @endif
                                        </a>
                                        <a href="{{ route('profile') }}" 
                                           class="relative py-2 text-sm font-bold transition-colors font-label-lg {{ request()->routeIs('profile') ? 'text-[#9b4500]' : 'text-[#897266] hover:text-[#9b4500]' }}">
                                            Profile
                                            @if(request()->routeIs('profile'))
                                                <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-[#9b4500] rounded-full"></span>
                                            @endif
                                        </a>
                                    </div>

                                    <!-- Right side Actions (Cart, Notifications, Account) -->
                                    <div class="flex items-center gap-5">
                                        <!-- Shopping Cart Icon -->
                                        @php
                                            $cartItems = session('cart', []);
                                            $cartCount = array_sum($cartItems);
                                        @endphp
                                        <a href="{{ $myOrdersUrl }}" class="relative p-2 text-[#897266] hover:text-[#9b4500] hover:bg-[#ffab69]/10 rounded-xl transition-all">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                            @if($cartCount > 0)
                                                <span class="absolute top-1 right-1 w-5 h-5 bg-[#9b4500] text-white text-xs font-bold flex items-center justify-center rounded-full border-2 border-[#fff8f6]">
                                                    {{ $cartCount }}
                                                </span>
                                            @endif
                                        </a>

                                        <!-- Notification Icon -->
                                        <button class="relative p-2 text-[#897266] hover:text-[#9b4500] hover:bg-[#ffab69]/10 rounded-xl transition-all">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                                            <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-[#9b4500] rounded-full border border-[#fff8f6]"></span>
                                        </button>

                                        <!-- Profile Dropdown Button -->
                                        <form method="POST" action="{{ route('logout') }}" class="flex items-center">
                                            @csrf
                                            <button type="submit" title="Log Out" class="p-1 rounded-full border-2 border-[#ffab69] hover:opacity-80 transition-all">
                                                <img src="{{ auth()->user()->photo_path ? asset('storage/' . auth()->user()->photo_path) : 'https://api.dicebear.com/7.x/adventurer/svg?seed=' . auth()->user()->name }}" 
                                                     alt="Avatar" 
                                                     class="w-8 h-8 rounded-full object-cover bg-orange-100" />
                                            </button>
                                        </form>
                                    </div>
                                </header>
                            @endif

                            <!-- Content Slot -->
                            <main class="flex-1 overflow-y-auto p-8">
                                {{ $slot }}
                            </main>
                        </div>
                    </div>
                @else
                    <!-- Penjual / Admin: slot saja, sidebar diatur masing-masing view -->
                    <div class="min-h-screen bg-gray-50">
                        {{ $slot }}
                    </div>
                @endif
            @else
                <!-- Guest Layout -->
                <div class="min-h-screen bg-gray-50 flex flex-col justify-center items-center">
                    {{ $slot }}
                </div>
            @endauth
        </div>
    </body>
</html>
