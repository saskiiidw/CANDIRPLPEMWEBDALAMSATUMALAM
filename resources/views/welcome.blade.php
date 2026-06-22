<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SmartCanteen - Pesan Tanpa Antre. Nikmati Hidangan.</title>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Work+Sans:wght@400;500;600&display=swap" rel="stylesheet">

        <!-- Styles & Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-body-md bg-[#fff8f6] text-[#231914] selection:bg-primary/20 selection:text-primary">
        <div class="relative min-h-screen flex flex-col">
            
            <!-- Header / Navbar -->
            <header class="w-full max-w-7xl mx-auto px-6 py-6 flex items-center justify-between">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="font-display-lg text-2xl font-extrabold text-primary tracking-tight hover:opacity-90 transition">
                    SmartCanteen
                </a>

                <!-- Navigation Links -->
                <nav class="hidden md:flex items-center gap-8">
                    @auth
                        <a href="{{ route('home.student') }}" class="text-sm font-medium text-[#564338] hover:text-primary transition">
                            Menu
                        </a>
                        <a href="{{ route('orders.history') }}" class="text-sm font-medium text-[#564338] hover:text-primary transition">
                            Pesanan
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-[#564338] hover:text-primary transition">
                            Menu
                        </a>
                        <a href="{{ route('login') }}" class="text-sm font-medium text-[#564338] hover:text-primary transition">
                            Pesanan
                        </a>
                    @endauth
                    <!-- Scroll link to features as "About" -->
                    <a href="#features" class="text-sm font-medium text-primary border-b-2 border-primary pb-0.5 transition">
                        Tentang
                    </a>
                </nav>

                <!-- Authentication Buttons -->
                <div class="flex items-center gap-4">
                    @auth
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-semibold text-[#564338]/80 hidden sm:inline">Hai, {{ auth()->user()->name }}</span>
                            <a href="{{ url('/dashboard') }}" class="bg-[#9b4500] hover:bg-[#803800] text-white font-semibold text-xs sm:text-sm px-5 py-2.5 rounded-full transition shadow-sm">
                                Dasbor
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-xs sm:text-sm font-medium text-[#564338] hover:text-primary transition ml-2">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-[#564338] hover:text-primary transition">
                            Masuk
                        </a>
                        <a href="{{ route('register', ['role' => 'penjual']) }}" class="bg-[#9b4500] hover:bg-[#803800] text-white font-semibold text-xs sm:text-sm px-5 py-2.5 rounded-full transition shadow-sm">
                            Daftar Penjual
                        </a>
                    @endauth
                </div>
            </header>

            <!-- Main Content Container -->
            <main class="flex-grow">
                
                <!-- Hero Section -->
                <section class="max-w-7xl mx-auto px-6 py-12 lg:py-20 grid lg:grid-cols-12 gap-12 items-center">
                    
                    <!-- Left Hero: Headlines & CTAs -->
                    <div class="lg:col-span-7 space-y-6">
                        <!-- Floating Badge -->
                        <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#fdeee7] text-primary text-xs font-semibold rounded-full border border-[#fbdcd0] w-fit">
                            <!-- Utensils / Smart Icon -->
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Smart Canteen Experience
                        </div>

                        <!-- Headlines -->
                        <h1 class="font-display-lg text-4xl sm:text-5xl md:text-6xl font-extrabold text-[#231914] leading-[1.1] tracking-tight">
                            Pesan Tanpa Antre.<br>Nikmati <span class="text-primary">Hidangan</span>.
                        </h1>

                        <!-- Description -->
                        <p class="font-body-lg text-[#564338]/90 text-base sm:text-lg leading-relaxed max-w-xl">
                            Nikmati hidangan kampus yang sehat tanpa repot. Pesan menu favorit Anda, pantau persiapan secara real-time, dan ambil tepat setelah siap disajikan.
                        </p>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-4 pt-2">
                            @auth
                                <a href="{{ route('home.student') }}" class="bg-[#9b4500] hover:bg-[#803800] text-white font-semibold text-base px-8 py-3.5 rounded-full shadow-md hover:shadow-lg transition">
                                    Mulai Memesan
                                </a>
                                <a href="{{ route('home.student') }}" class="bg-[#feeae0] hover:bg-[#fbdcd0] text-[#9b4500] font-semibold text-base px-8 py-3.5 rounded-full border border-[#fbdcd0] transition">
                                    Lihat Menu
                                </a>
                            @else
                                <a href="{{ route('register.choice') }}" class="bg-[#9b4500] hover:bg-[#803800] text-white font-semibold text-base px-8 py-3.5 rounded-full shadow-md hover:shadow-lg transition">
                                    Mulai Memesan
                                </a>
                                <a href="{{ route('login') }}" class="bg-[#feeae0] hover:bg-[#fbdcd0] text-[#9b4500] font-semibold text-base px-8 py-3.5 rounded-full border border-[#fbdcd0] transition">
                                    Lihat Menu
                                </a>
                            @endauth
                        </div>
                    </div>

                    <!-- Right Hero: Graphic Assets -->
                    <div class="lg:col-span-5 relative flex justify-center">
                        <!-- Custom Curved Hero Container -->
                        <div class="relative w-full max-w-[420px] aspect-square rounded-tl-[32px] rounded-tr-[96px] rounded-bl-[96px] rounded-br-[32px] overflow-hidden shadow-2xl border-4 border-white/50">
                            <img 
                                src="{{ asset('images/hero-salad.png') }}" 
                                alt="Delicious Campus Canteen Meal Bowl" 
                                class="w-full h-full object-cover transform hover:scale-105 transition duration-500"
                            >
                        </div>

                        <!-- Overlapping Floating Slot Card -->
                        <div class="absolute -bottom-4 -left-4 sm:-left-10 bg-white/95 backdrop-blur-sm shadow-xl border border-gray-100/50 rounded-2xl p-4 flex items-center gap-3 z-10 max-w-[210px] transform hover:-translate-y-1 transition duration-300">
                            <!-- Clock Icon -->
                            <div class="w-10 h-10 rounded-full bg-[#fdeee7] flex items-center justify-center text-primary flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Jadwal Pengambilan</span>
                                <span class="text-sm font-extrabold text-[#9b4500]">Dalam 15 menit</span>
                            </div>
                        </div>
                    </div>

                </section>

                <!-- Designed for Campus Life Features Grid Section -->
                <section id="features" class="max-w-7xl mx-auto px-6 py-16 lg:py-24 space-y-16">
                    <!-- Heading -->
                    <div class="text-center space-y-4 max-w-xl mx-auto">
                        <h2 class="font-display-lg text-3xl sm:text-4xl font-extrabold text-[#231914] tracking-tight">
                            Didesain untuk Kehidupan Kampus
                        </h2>
                        <p class="font-body-md text-[#564338]/85 text-base sm:text-lg leading-relaxed">
                            Semua yang Anda butuhkan untuk mengelola makanan harian secara efisien dalam satu antarmuka yang ramah pengguna.
                        </p>
                    </div>

                    <!-- Asymmetric Bento Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        
                        <!-- Card 1: Effortless Pre-ordering (Wide, 2 cols) -->
                        <div class="lg:col-span-2 bg-white rounded-[32px] p-8 border border-[#feeae0] flex flex-col justify-between overflow-hidden shadow-sm hover:shadow-md transition duration-300 group">
                            <div>
                                <!-- Icon Circle -->
                                <div class="bg-[#fdeee7] p-3 rounded-2xl w-fit mb-6 text-primary transition-transform duration-300 group-hover:scale-110">
                                    <!-- Shopping Bag Icon -->
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                </div>
                                <!-- Text content -->
                                <h3 class="font-headline-md text-xl text-[#231914] mb-3">Pemesanan yang Mudah</h3>
                                <p class="font-body-md text-[#564338]/90 text-sm leading-relaxed max-w-md mb-8">
                                    Jelajahi menu dari seluruh gerai kampus, sesuaikan hidangan Anda, dan bayar di awal untuk melewati antrean sepenuhnya.
                                </p>
                            </div>
                            <!-- Card Decorative Graphic -->
                            <div class="w-full h-24 bg-gradient-to-r from-[#feeae0] to-[#fff8f6] rounded-2xl relative overflow-hidden mt-auto flex items-center justify-between px-6 border border-[#feeae0]/35">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-primary font-bold shadow-sm">1</div>
                                    <div class="w-10 h-10 rounded-full bg-white/70 flex items-center justify-center text-[#564338]/70 font-bold">2</div>
                                    <div class="w-10 h-10 rounded-full bg-white/40 flex items-center justify-center text-[#564338]/40 font-bold">3</div>
                                </div>
                                <span class="text-xs font-bold text-primary tracking-wider uppercase bg-white/80 px-3 py-1 rounded-full shadow-sm">Pembayaran Cepat</span>
                            </div>
                        </div>

                        <!-- Card 2: Smart ETA (Narrow, 1 col) -->
                        <div class="lg:col-span-1 bg-white rounded-[32px] p-8 border border-[#feeae0] flex flex-col justify-between shadow-sm hover:shadow-md transition duration-300 group">
                            <div>
                                <!-- Icon Circle -->
                                <div class="bg-[#fdeee7] p-3 rounded-2xl w-fit mb-6 text-primary transition-transform duration-300 group-hover:scale-110">
                                    <!-- Clock Icon -->
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="font-headline-md text-xl text-[#231914] mb-3">Estimasi Waktu Pintar</h3>
                                <p class="font-body-md text-[#564338]/90 text-sm leading-relaxed mb-8">
                                    Ketahui secara pasti kapan makanan Anda siap berdasarkan antrean dapur saat ini.
                                </p>
                            </div>
                            <!-- Graphic: Prep Badge -->
                            <div class="bg-[#fdeee7] border border-[#fbdcd0] rounded-2xl p-4 flex items-center gap-3 mt-auto">
                                <div class="bg-white p-2.5 rounded-full text-primary shadow-sm flex items-center justify-center flex-shrink-0 animate-pulse">
                                    <!-- Loading Dots Icon -->
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider block">Estimasi Persiapan</span>
                                    <span class="text-lg font-extrabold text-[#9b4500]">8–12 menit</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: Live Status (Narrow, 1 col) -->
                        <div class="lg:col-span-1 bg-white rounded-[32px] p-8 border border-[#feeae0] flex flex-col justify-between shadow-sm hover:shadow-md transition duration-300 group">
                            <div>
                                <!-- Icon Circle -->
                                <div class="bg-[#fdeee7] p-3 rounded-2xl w-fit mb-6 text-primary transition-transform duration-300 group-hover:scale-110">
                                    <!-- Truck Icon -->
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m10 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                    </svg>
                                </div>
                                <h3 class="font-headline-md text-xl text-[#231914] mb-3">Status Langsung</h3>
                                <p class="font-body-md text-[#564338]/90 text-sm leading-relaxed mb-8">
                                    Pantau proses pesanan Anda dari "Diterima", "Sedang Disiapkan", hingga "Siap Diambil".
                                </p>
                            </div>
                            <!-- Tracker Visual mockup -->
                            <div class="space-y-4 mt-auto">
                                <!-- Tracker Row 1 -->
                                <div class="h-1.5 w-full bg-gray-100 rounded-full relative">
                                    <div class="absolute -top-1 left-0 w-3.5 h-3.5 rounded-full bg-gray-300 border-2 border-white shadow-sm"></div>
                                </div>
                                <!-- Tracker Row 2 (Active/Preparing) -->
                                <div class="h-1.5 w-full bg-[#feeae0] rounded-full relative flex items-center justify-between">
                                    <div class="absolute -top-1 left-0 w-3.5 h-3.5 rounded-full bg-[#9b4500] border-2 border-white shadow-sm"></div>
                                    <div class="absolute right-0 bg-[#fdeee7] border border-[#fbdcd0] text-[#9b4500] text-[10px] font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                                        Disiapkan
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 4: Real-Time Inventory (Wide, 2 cols - Dark Orange/Brown Theme) -->
                        <div class="lg:col-span-2 bg-[#9b4500] rounded-[32px] p-8 relative overflow-hidden text-white flex flex-col justify-between shadow-md hover:shadow-lg transition duration-300 group" style="background-image: radial-gradient(rgba(255,255,255,0.12) 1.5px, transparent 1.5px); background-size: 18px 18px;">
                            <div>
                                <!-- Icon Circle (White Box) -->
                                <div class="bg-[#803800] p-3 rounded-2xl w-fit mb-6 text-white transition-transform duration-300 group-hover:scale-110 border border-white/10">
                                    <!-- Inventory Box Icon -->
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <h3 class="font-headline-md text-xl text-white mb-3">Stok Real-Time</h3>
                                <p class="font-body-md text-[#ffede5]/95 text-sm leading-relaxed max-w-md mb-8">
                                    Tidak ada lagi memesan menu yang sudah habis. Menu kami tersinkronisasi instan dengan dapur kantin, menampilkan menu segar yang tersedia saat ini.
                                </p>
                            </div>
                            <!-- Floating Mockup Menu Item Card -->
                            <div class="bg-white rounded-2xl p-4 text-[#231914] shadow-xl max-w-[270px] self-end mt-4 transform translate-x-2 translate-y-2 border border-gray-100/50 flex-shrink-0 group-hover:-translate-y-1 transition duration-300">
                                <div class="flex items-center justify-between border-b border-gray-100 pb-2 mb-3">
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Spesial Hari Ini</span>
                                    <span class="text-[9px] font-extrabold text-red-500 bg-red-50 border border-red-100 px-2 py-0.5 rounded-full">Sisa 3</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-full bg-[#fdeee7] flex-shrink-0 overflow-hidden border border-gray-100">
                                        <img src="{{ asset('images/hero-salad.png') }}" alt="Artisan Chicken Wrap Mockup" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-extrabold text-[#231914] leading-tight">Artisan Chicken Wrap</h4>
                                        <p class="text-xs font-bold text-primary mt-0.5">$6.60</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>

            </main>

            <!-- Footer -->
            <footer class="w-full bg-[#feeae0]/40 border-t border-[#feeae0] mt-16">
                <div class="max-w-7xl mx-auto px-6 py-12 flex flex-col md:flex-row items-center justify-between gap-6">
                    
                    <!-- Left: Branding & Copyright -->
                    <div class="text-center md:text-left space-y-2">
                        <span class="font-display-lg text-lg font-bold text-primary">SmartCanteen</span>
                        <p class="text-xs text-[#564338]/70">
                            &copy; {{ date('Y') }} SmartCanteen. Menyajikan hidangan terbaik untuk kampus Anda.
                        </p>
                    </div>

                    <!-- Right: Legal & Help Links -->
                    <div class="flex flex-wrap justify-center gap-6">
                        <a href="#" class="text-xs text-[#564338]/80 hover:text-primary transition">Kebijakan Privasi</a>
                        <a href="#" class="text-xs text-[#564338]/80 hover:text-primary transition">Ketentuan Layanan</a>
                        <a href="#" class="text-xs text-[#564338]/80 hover:text-primary transition">Pusat Bantuan</a>
                        <a href="#" class="text-xs text-[#564338]/80 hover:text-primary transition">Hubungi Kami</a>
                    </div>
                </div>
            </footer>

        </div>
    </body>
</html>
