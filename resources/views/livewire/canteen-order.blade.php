<div class="max-w-5xl mx-auto space-y-8">
    @if($step === 'menu')
        <!-- ================================================================= -->
        <!-- STEP 1: CANTEEN DETAIL VIEW (Image 2)                            -->
        <!-- ================================================================= -->
        
        @php
            $canteen = \App\Models\User::find($sellerId);
            $canteenName = $canteen->store_name ?? 'The Hungry Scholar';
            $canteenDesc = $canteen->description ?? 'Nourishing the minds of tomorrow with wholesome, comforting meals. Specializing in hearty mains and fresh artisan sides.';
            $canteenRating = round(4.0 + (crc32($canteen->email ?? '') % 10) / 10, 1);
        @endphp

        <!-- Hero Canteen Section -->
        <div class="relative rounded-[2.5rem] overflow-hidden shadow-md h-72 md:h-96 border border-[#feeae0]">
            <!-- Hero Cover Image -->
            <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1200&q=80" 
                 alt="Canteen Cover" 
                 class="w-full h-full object-cover brightness-75">
            
            <!-- Float Canteen Detail Card (Overlap) -->
            <div class="absolute left-6 right-6 bottom-6 md:left-10 md:bottom-8 bg-white/95 backdrop-blur-md rounded-[2rem] p-8 border border-white/20 shadow-xl max-w-xl space-y-4">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-100 text-red-700 text-[10px] font-extrabold rounded-full">
                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span>
                        Buka Sekarang
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 border border-amber-100 text-[#9b4500] text-[10px] font-extrabold rounded-full">
                        ★ {{ $canteenRating }}
                    </span>
                    <span class="text-xs text-gray-500 font-semibold">
                        ⏰ 07:00 AM - 09:00 PM
                    </span>
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#231914] tracking-tight leading-none">{{ $canteenName }}</h1>
                <p class="text-xs md:text-sm text-[#897266] leading-relaxed">{{ $canteenDesc }}</p>
                
                <!-- Search menu input -->
                <div class="relative max-w-md pt-2">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none pt-2">
                        <svg class="h-4 w-4 text-[#897266]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari item menu..." 
                        class="block w-full pl-10 pr-4 py-2.5 text-xs bg-[#FDF6F0] border border-[#f2dfd5] focus:border-[#fca366] focus:ring-[#fca366] rounded-full text-[#231914] placeholder-[#897266]/70 shadow-sm"
                    />
                </div>
            </div>
        </div>

        @if($stockError)
            <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-xs rounded-r-xl font-bold">
                {{ $stockError }}
            </div>
        @endif

        <!-- Menu Categories and Grid Layout -->
        <div class="flex flex-col md:flex-row gap-8 items-start">
            <!-- Sidebar: Menu Categories (Image 2) -->
            <aside class="w-full md:w-60 bg-transparent p-0 space-y-4 flex-shrink-0">
                <h3 class="text-base font-extrabold text-[#231914] tracking-tight pl-2">Kategori Menu</h3>
                <nav class="flex md:flex-col flex-wrap gap-2">
                    @foreach([
                        ['label' => 'Semua Menu', 'value' => ''],
                        ['label' => 'Makanan Berat', 'value' => 'makanan_berat'],
                        ['label' => 'Makanan Ringan', 'value' => 'makanan_ringan'],
                        ['label' => 'Minuman', 'value' => 'minuman']
                    ] as $cat)
                        <button 
                            wire:click="$set('category', '{{ $cat['value'] }}')"
                            class="text-left px-5 py-3.5 rounded-full text-xs font-bold transition-all active:scale-95 border {{ $category === $cat['value'] ? 'bg-[#fca366] text-[#4d1f00] border-[#fca366] font-extrabold shadow-sm' : 'bg-[#FDF6F0] text-[#897266] border-[#f2dfd5]/40 hover:bg-[#fca366]/10' }}"
                        >
                            {{ $cat['label'] }}
                        </button>
                    @endforeach
                </nav>
            </aside>

            <!-- Food Grid -->
            <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($filteredMenus as $menu)
                    <div 
                        class="group bg-white border border-[#feeae0] hover:border-[#ffab69] rounded-[2rem] overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-between"
                        wire:key="menu-{{ $menu->id }}"
                    >
                        <!-- Food Card Header -->
                        <div class="relative h-48 bg-orange-50 overflow-hidden">
                            @php
                                $imgUrl = match($menu->name) {
                                    "Scholar's Grain Bowl" => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=400&q=80',
                                    'Classic Grilled Cheese' => 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?auto=format&fit=crop&w=400&q=80',
                                    'Iced Library Matcha' => 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?auto=format&fit=crop&w=400&q=80',
                                    'Artisan Chicken Wrap' => 'https://images.unsplash.com/photo-1626700051175-6518c4793f4f?auto=format&fit=crop&w=400&q=80',
                                    'Classic Campus Burger' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=400&q=80',
                                    'Garden Salad Bowl' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=400&q=80',
                                    default => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=400&q=80'
                                };
                            @endphp
                            <img 
                                src="{{ $menu->photo_path ? asset('storage/' . $menu->photo_path) : $imgUrl }}" 
                                alt="{{ $menu->name }}" 
                                class="w-full h-full object-cover transition-all duration-500 group-hover:scale-105"
                            />
                            
                            <!-- Badges -->
                            <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                                @if($menu->stock == 0)
                                    <span class="px-3 py-1 bg-gray-500/90 text-white text-[9px] font-extrabold rounded-full uppercase tracking-wider">
                                        Habis
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-white/95 text-gray-800 text-[9px] font-extrabold rounded-full shadow-sm">
                                        Tersisa {{ $menu->stock }}
                                    </span>
                                    @if($menu->stock <= 10)
                                        <span class="px-3 py-1 bg-orange-100 text-orange-700 text-[9px] font-extrabold rounded-full uppercase tracking-wider">
                                            Laris
                                        </span>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 flex-1 flex flex-col justify-between space-y-4 bg-gradient-to-b from-white to-[#fff8f6]">
                            <div>
                                <h4 class="font-extrabold text-[#231914] text-base leading-tight font-headline-md">
                                    {{ $menu->name }}
                                </h4>
                                <p class="text-xs text-[#897266] mt-1.5 line-clamp-2">
                                    {{ $menu->description ?? 'Savory and delicious campus favorite.' }}
                                </p>
                            </div>

                            <div class="flex items-center justify-between pt-3 border-t border-gray-100/60">
                                <span class="text-[#8c3b03] font-extrabold text-lg">
                                    Rp {{ number_format($menu->price, 0, ',', '.') }}
                                </span>
                                
                                @if($menu->stock > 0)
                                    <button 
                                        wire:click="openCustomize({{ $menu->id }})"
                                        class="w-9 h-9 bg-[#8c3b03] hover:bg-[#a64605] text-white flex items-center justify-center rounded-full transition-all shadow-md active:scale-90"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                @else
                                    <div class="w-9 h-9 bg-[#feeae0]/60 text-gray-400 flex items-center justify-center rounded-full cursor-not-allowed">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Sticky Cart Bar (Image 2 Bottom Floating) -->
        @php
            $totalItems = array_sum($cart);
        @endphp
        @if($totalItems > 0)
            <div class="fixed bottom-6 right-6 z-50">
                <button 
                    wire:click="goToCheckout"
                    class="flex items-center gap-3 px-6 py-4 bg-[#8c3b03] hover:bg-[#a64605] text-white font-extrabold rounded-full transition-all shadow-xl hover:scale-105 active:scale-95"
                >
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="text-xs tracking-wider">Lihat Keranjang (Rp {{ number_format($this->totalPrice, 0, ',', '.') }})</span>
                </button>
            </div>
        @endif

    @elseif($step === 'customize')
        <!-- ================================================================= -->
        <!-- STEP 2: CUSTOMIZE VIEW (Image 3)                                  -->
        <!-- ================================================================= -->
        @php
            $menu = $menus->firstWhere('id', $selectedMenuId);
        @endphp
        @if($menu)
            <div class="space-y-6 max-w-4xl mx-auto">
                <!-- Back Link -->
                <button wire:click="closeCustomize" class="flex items-center gap-2 text-xs font-extrabold text-[#9b4500] hover:underline">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Menu
                </button>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-stretch">
                    <!-- Left: Large Image Card -->
                    <div class="relative rounded-[2.5rem] overflow-hidden shadow-md min-h-[300px] md:h-auto bg-orange-50 border border-[#feeae0]">
                        @php
                            $imgUrl = match($menu->name) {
                                "Scholar's Grain Bowl" => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=600&q=80',
                                'Classic Grilled Cheese' => 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?auto=format&fit=crop&w=600&q=80',
                                'Iced Library Matcha' => 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?auto=format&fit=crop&w=600&q=80',
                                'Artisan Chicken Wrap' => 'https://images.unsplash.com/photo-1626700051175-6518c4793f4f?auto=format&fit=crop&w=600&q=80',
                                'Classic Campus Burger' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=600&q=80',
                                'Garden Salad Bowl' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=600&q=80',
                                default => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80'
                            };
                        @endphp
                        <img 
                            src="{{ $menu->photo_path ? asset('storage/' . $menu->photo_path) : $imgUrl }}" 
                            alt="{{ $menu->name }}" 
                            class="w-full h-full object-cover"
                        />
                        <div class="absolute top-4 left-4 flex gap-2">
                            <span class="px-3 py-1 bg-amber-500 text-white text-[10px] font-extrabold rounded-full">Populer</span>
                            <span class="px-3 py-1 bg-emerald-600 text-white text-[10px] font-extrabold rounded-full">Tinggi Protein</span>
                        </div>
                    </div>

                    <!-- Right: Customization Details -->
                    <div class="bg-white border border-[#feeae0] rounded-[2.5rem] p-8 flex flex-col justify-between space-y-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-baseline">
                                <h1 class="text-3xl font-extrabold text-[#231914] tracking-tight leading-tight">{{ $menu->name }}</h1>
                                <span class="text-2xl font-extrabold text-[#8c3b03]">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                            </div>
                            
                            <p class="text-xs md:text-sm text-[#897266] leading-relaxed">
                                {{ $menu->description ?? 'Savory and delicious campus favorite.' }}
                            </p>

                            <!-- Meta Info -->
                            <div class="flex gap-4 text-xs font-bold text-gray-500 pt-2">
                                <span class="inline-flex items-center gap-1.5">
                                    ⏱ {{ $menu->cooking_time_minutes }} mnt
                                </span>
                                <span class="inline-flex items-center gap-1.5">
                                    🔥 {{ 250 + (crc32($menu->name) % 30) * 10 }} kcal
                                </span>
                            </div>

                            <!-- Special notes -->
                            <div class="space-y-2 pt-4">
                                <label class="block text-xs font-extrabold text-[#231914] uppercase tracking-wider">Catatan Khusus</label>
                                <textarea 
                                    wire:model="customizeNote"
                                    placeholder="Cth., Tanpa bawang, pedas..."
                                    rows="3" 
                                    class="block w-full border border-[#f2dfd5] focus:border-[#fca366] focus:ring-[#fca366] bg-[#FDF6F0] rounded-2xl text-xs placeholder-gray-400 p-4"
                                ></textarea>
                            </div>
                        </div>

                        <!-- Action Bar -->
                        <div class="flex items-center justify-between pt-6 border-t border-[#feeae0] gap-4">
                            <!-- Qty selector horizontal oval -->
                            <div class="flex items-center bg-[#FAF3EB] border border-[#f2dfd5] rounded-full p-1 shadow-sm">
                                <button 
                                    wire:click="decrementCustomize" 
                                    class="w-9 h-9 flex items-center justify-center bg-white border border-[#f2dfd5] hover:border-[#fca366] text-[#9b4500] font-extrabold rounded-full transition-all active:scale-90"
                                >
                                    -
                                </button>
                                <span class="px-5 text-sm font-extrabold text-[#231914]">{{ $customizeQuantity }}</span>
                                <button 
                                    wire:click="incrementCustomize" 
                                    class="w-9 h-9 flex items-center justify-center bg-white border border-[#f2dfd5] hover:border-[#fca366] text-[#9b4500] font-extrabold rounded-full transition-all active:scale-90"
                                >
                                    +
                                </button>
                            </div>

                            <!-- Big orange add button -->
                            <button 
                                wire:click="addCustomizeToCart"
                                class="flex-1 py-4 bg-[#8c3b03] hover:bg-[#a64605] text-white text-xs font-bold rounded-full transition-all shadow-md active:scale-95"
                            >
                                Tambah ke Keranjang (Rp {{ number_format($menu->price * $customizeQuantity, 0, ',', '.') }})
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    @elseif($step === 'checkout')
        <!-- ================================================================= -->
        <!-- STEP 3: CHECKOUT / CART VIEW (Image 4)                            -->
        <!-- ================================================================= -->
        <div class="space-y-6 max-w-4xl mx-auto">
            <!-- Back Link -->
            <button wire:click="goToMenu" class="flex items-center gap-2 text-xs font-extrabold text-[#9b4500] hover:underline">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Menu
            </button>

            <!-- Page Title -->
            <div>
                <h1 class="text-3xl font-extrabold text-[#231914] tracking-tight leading-none font-headline-lg">Pembayaran</h1>
                <p class="text-xs text-[#897266] mt-1.5">Tinjau pesanan Anda dan selesaikan pembayaran.</p>
            </div>

            @if($stockError)
                <div class="p-4 bg-red-100 border-l-4 border-red-500 text-red-700 text-xs rounded-r-xl font-bold">
                    {{ $stockError }}
                </div>
            @endif

            <!-- Layout Split -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                <!-- Left: Your Order Panel -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white border border-[#feeae0] rounded-[2rem] p-6 space-y-6 shadow-sm">
                        <h3 class="text-lg font-bold text-[#231914] pb-3 border-b border-[#feeae0]">Pesanan Anda</h3>

                        <div class="space-y-4">
                            @foreach($cart as $menuId => $qty)
                                @php
                                    $menu = $menus->firstWhere('id', $menuId);
                                @endphp
                                @if($menu)
                                    @php
                                        $imgUrl = match($menu->name) {
                                            "Scholar's Grain Bowl" => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=150&q=80',
                                            'Classic Grilled Cheese' => 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?auto=format&fit=crop&w=150&q=80',
                                            'Iced Library Matcha' => 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?auto=format&fit=crop&w=150&q=80',
                                            'Artisan Chicken Wrap' => 'https://images.unsplash.com/photo-1626700051175-6518c4793f4f?auto=format&fit=crop&w=150&q=80',
                                            'Classic Campus Burger' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=150&q=80',
                                            'Garden Salad Bowl' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=150&q=80',
                                            default => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=150&q=80'
                                        };
                                    @endphp
                                    <div class="flex items-start gap-4 p-4 rounded-2xl border border-[#feeae0] bg-[#fff8f6]" wire:key="cart-item-{{ $menu->id }}">
                                        <!-- Image -->
                                        <img src="{{ $menu->photo_path ? asset('storage/' . $menu->photo_path) : $imgUrl }}" 
                                             alt="{{ $menu->name }}" 
                                             class="w-16 h-16 rounded-xl object-cover border border-[#f2dfd5] bg-orange-100 shadow-sm" />
                                        
                                        <!-- Detail -->
                                        <div class="flex-1 space-y-2">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h4 class="font-bold text-[#231914] text-sm md:text-base">{{ $menu->name }}</h4>
                                                    @if(isset($itemNotes[$menuId]) && !empty($itemNotes[$menuId]))
                                                        <p class="text-xs text-amber-700 italic mt-0.5">
                                                            "{{ $itemNotes[$menuId] }}"
                                                        </p>
                                                    @endif
                                                </div>
                                                <span class="font-extrabold text-[#8c3b03] text-sm md:text-base">Rp {{ number_format($menu->price * $qty, 0, ',', '.') }}</span>
                                            </div>

                                            <div class="flex justify-between items-center pt-2">
                                                <!-- Quantity selector in Checkout -->
                                                <div class="flex items-center bg-white border border-[#f2dfd5] rounded-full p-0.5 shadow-sm">
                                                    <button 
                                                        wire:click="decrementQty({{ $menu->id }})" 
                                                        class="w-7 h-7 flex items-center justify-center hover:bg-orange-50 text-[#9b4500] font-extrabold rounded-full transition-all"
                                                    >
                                                        -
                                                    </button>
                                                    <span class="px-3 text-xs font-extrabold text-[#231914]">{{ $qty }}</span>
                                                    <button 
                                                        wire:click="incrementQty({{ $menu->id }})" 
                                                        class="w-7 h-7 flex items-center justify-center hover:bg-orange-50 text-[#9b4500] font-extrabold rounded-full transition-all"
                                                    >
                                                        +
                                                    </button>
                                                </div>

                                                <!-- Remove button -->
                                                <button 
                                                    wire:click="removeFromCart({{ $menu->id }})"
                                                    class="text-xs font-bold text-red-600 hover:text-red-700 flex items-center gap-1 transition-colors"
                                                >
                                                    🗑 Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <!-- Notes text -->
                        <div class="space-y-2 pt-4 border-t border-[#feeae0]">
                            <label class="block text-xs font-extrabold text-[#231914] uppercase tracking-wider">Tambah Catatan Pesanan</label>
                            <textarea 
                                wire:model="note"
                                placeholder="Ada permintaan khusus? (Cth., butuh sendok, dipisah)..."
                                rows="3" 
                                class="block w-full border border-[#f2dfd5] focus:border-[#fca366] focus:ring-[#fca366] bg-[#FDF6F0] rounded-2xl text-xs placeholder-gray-400 p-4"
                            ></textarea>
                        </div>
                    </div>
                </div>

                <!-- Right: Wait & Summary -->
                <div class="space-y-6">
                    <!-- Wait Estimator (Image 4 right pane) -->
                    @php
                        $approxWait = $this->calculateEta();
                    @endphp
                    <div class="bg-[#FAF3EB] border border-[#f2dfd5] rounded-3xl p-5 flex items-center justify-between shadow-sm relative overflow-hidden">
                        <div class="flex items-center gap-3.5">
                            <!-- Timer Icon -->
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-[#9b4500] border border-[#feeae0] shadow-sm flex-shrink-0">
                                ⏱
                            </div>
                            <div>
                                <span class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wide">Estimasi Waktu</span>
                                <span class="text-lg font-extrabold text-gray-800 leading-none">{{ $approxWait - 2 }} - {{ $approxWait + 2 }} mnt</span>
                            </div>
                        </div>
                        
                        <!-- simple loader spinner -->
                        <div class="w-8 h-8 rounded-full border-4 border-orange-200 border-t-[#8c3b03] animate-spin"></div>
                    </div>

                    <!-- Checkout Summary -->
                    @php
                        $subTotal = $this->totalPrice;
                        $tax = (int) round($subTotal * 0.08);
                        $campusFee = 500;
                        $grandTotal = $subTotal + $tax + $campusFee;
                    @endphp
                    <div class="bg-white border border-[#feeae0] rounded-3xl p-6 space-y-4 shadow-sm flex flex-col">
                        <h3 class="text-lg font-bold text-[#231914] pb-2 border-b border-[#feeae0]">Ringkasan</h3>

                        <div class="space-y-2 text-xs font-bold text-gray-500">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span class="text-[#231914] font-extrabold">Rp {{ number_format($subTotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Pajak (8%)</span>
                                <span class="text-[#231914] font-extrabold">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Biaya Layanan</span>
                                <span class="text-[#231914] font-extrabold">Rp {{ number_format($campusFee, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-[#feeae0] flex justify-between items-baseline">
                            <span class="text-sm font-bold text-gray-700">Total</span>
                            <span class="text-3xl font-extrabold text-[#8c3b03]">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>

                        <button 
                            wire:click="checkout"
                            class="w-full py-4 mt-4 bg-[#8c3b03] hover:bg-[#a64605] text-white text-xs font-extrabold rounded-full transition-all shadow-md active:scale-95 flex items-center justify-center gap-2"
                        >
                            🔒 Buat Pesanan
                        </button>
                        <span class="block text-center text-[9px] text-gray-400 mt-2">Pembayaran aman dan terenkripsi.</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Payment Sandbox Modal -->
    @if($showPaymentModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
            <div class="bg-white border border-[#feeae0] rounded-3xl shadow-2xl p-6 md:p-8 max-w-md w-full space-y-6">
                <div class="text-center space-y-3">
                    <div class="w-14 h-14 bg-green-50 text-green-700 mx-auto rounded-full flex items-center justify-center text-2xl shadow-inner">
                        💸
                    </div>
                    <h3 class="text-xl font-extrabold text-[#231914]">Konfirmasi Pembayaran</h3>
                    <p class="text-xs text-gray-500">Silakan konfirmasi pembayaran Anda menggunakan saldo akun untuk menyelesaikan pesanan.</p>
                </div>

                <div class="p-4 bg-[#FAF3EB] rounded-2xl border border-[#f2dfd5] space-y-2 text-xs font-semibold text-gray-600">
                    <div class="flex justify-between">
                        <span>Total Tagihan</span>
                        <span class="text-[#9b4500] font-bold">Rp {{ number_format($this->totalPrice + (int) round($this->totalPrice * 0.08) + 500, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Metode Pembayaran</span>
                        <span class="text-gray-800 font-bold">SmartPay (Saldo Akun)</span>
                    </div>
                </div>

                <div class="flex gap-4 pt-2">
                    <button 
                        wire:click="cancelPaymentModal"
                        class="flex-1 py-3 bg-[#FAF3EB] border border-[#f2dfd5] text-xs font-bold text-gray-600 rounded-full transition-all"
                    >
                        Batal
                    </button>
                    <button 
                        wire:click="simulatePayment"
                        class="flex-1 py-3 bg-[#2e7d32] hover:bg-[#1b5e20] text-xs font-extrabold text-white rounded-full transition-all shadow-md active:scale-95"
                    >
                        Bayar Sekarang
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
