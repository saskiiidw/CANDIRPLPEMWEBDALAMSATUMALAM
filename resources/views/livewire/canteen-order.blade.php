<div class="max-w-5xl mx-auto space-y-8" x-data="{ searchMenu: '' }">
    @if($step === 'menu')
        <!-- ================================================================= -->
        <!-- STEP 1: CANTEEN DETAIL VIEW (Slide 2)                            -->
        <!-- ================================================================= -->
        
        @php
            $canteen = \App\Models\User::find($sellerId);
            $canteenName = $canteen->store_name ?? 'Kantin Mas Eddy';
            $canteenDesc = $canteen->description ?? 'Nourishing the minds of tomorrow with wholesome, comforting meals. Specializing in hearty mains and fresh artisan sides.';
            $canteenRating = round(4.0 + (crc32($canteen->email ?? '') % 10) / 10, 1);
        @endphp

        <!-- Hero Canteen Section (Overlap Style) -->
        <div class="relative rounded-[32px] overflow-hidden shadow-md h-64 md:h-80 border border-[#feeae0]">
            <!-- Hero Cover Image -->
            <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1200&q=80" 
                 alt="Canteen Cover" 
                 class="w-full h-full object-cover brightness-75">
            
            <!-- Float Canteen Detail Card (Overlap) -->
            <div class="absolute left-4 right-4 bottom-4 md:left-8 md:bottom-6 bg-white/95 backdrop-blur-md rounded-2xl p-6 border border-white/20 shadow-lg max-w-xl space-y-3">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-100 text-red-700 text-[10px] font-extrabold rounded-full">
                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span>
                        Open Now
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-orange-50 border border-orange-100 text-[#9b4500] text-[10px] font-extrabold rounded-full">
                        ★ {{ $canteenRating }}
                    </span>
                    <span class="text-xs text-gray-500 font-semibold">
                        ⏰ 07:00 AM - 09:00 PM
                    </span>
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-[#231914] tracking-tight leading-none">{{ $canteenName }}</h1>
                <p class="text-xs md:text-sm text-gray-600 leading-relaxed">{{ $canteenDesc }}</p>
                
                <!-- Search menu input -->
                <div class="relative max-w-md pt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none pt-1">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        x-model="searchMenu"
                        placeholder="Search menu items..." 
                        class="block w-full pl-9 pr-4 py-2 text-xs bg-[#FAF3EB] border border-[#f2dfd5] focus:border-[#ffab69] focus:ring-[#ffab69] rounded-xl text-gray-800 placeholder-gray-500"
                    />
                </div>
            </div>
        </div>

        @if($stockError)
            <div class="p-4 bg-red-100 border-l-4 border-red-500 text-red-700 text-xs rounded-r-xl">
                {{ $stockError }}
            </div>
        @endif

        <!-- Menu Categories and Grid Layout -->
        <div class="flex flex-col md:flex-row gap-8 items-start">
            <!-- Sidebar: Menu Categories (Slide 2) -->
            <aside class="w-full md:w-56 bg-white border border-[#feeae0] rounded-3xl p-6 space-y-4 flex-shrink-0" x-data="{ selectedCat: 'All Items' }">
                <h3 class="text-sm font-extrabold text-[#231914] uppercase tracking-wider pb-2 border-b border-[#feeae0]">Categories</h3>
                <nav class="flex md:flex-col flex-wrap gap-2">
                    @foreach(['All Items', 'Mains', 'Sides', 'Beverages'] as $cat)
                        <button 
                            @click="selectedCat = '{{ $cat }}'"
                            :class="selectedCat === '{{ $cat }}' ? 'bg-[#8e4e14] text-white font-extrabold shadow-sm' : 'text-[#897266] hover:bg-[#FAF3EB]'"
                            class="flex-1 md:flex-none text-left px-4 py-3 rounded-2xl text-xs font-semibold transition-all active:scale-95"
                        >
                            {{ $cat }}
                        </button>
                    @endforeach
                </nav>
            </aside>

            <!-- Food Grid -->
            <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" x-data="{ 
                categoryMatches(menuCat, activeCat) {
                    if (activeCat === 'All Items') return true;
                    if (activeCat === 'Mains') return menuCat === 'makanan_berat';
                    if (activeCat === 'Sides') return menuCat === 'makanan_ringan';
                    if (activeCat === 'Beverages') return menuCat === 'minuman';
                    return true;
                }
            }">
                @foreach($menus as $menu)
                    <div 
                        x-show="categoryMatches('{{ $menu->category }}', $parent.selectedCat) && (searchMenu === '' || '{{ strtolower($menu->name) }}'.includes(searchMenu.toLowerCase()))"
                        class="group bg-white border border-[#feeae0] hover:border-[#ffab69] rounded-3xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-between"
                        wire:key="menu-{{ $menu->id }}"
                    >
                        <!-- Food Card Header -->
                        <div class="relative h-44 bg-orange-50 overflow-hidden">
                            @php
                                $imgUrl = match($menu->name) {
                                    'Nasi Goreng Spesial' => 'https://images.unsplash.com/photo-1512058564366-18510be2db19?auto=format&fit=crop&w=400&q=80',
                                    'Mie Ayam Bakso' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?auto=format&fit=crop&w=400&q=80',
                                    'Soto Ayam Lamongan' => 'https://images.unsplash.com/photo-1547928576-a4a33237eceb?auto=format&fit=crop&w=400&q=80',
                                    'Nasi Padang Lengkap' => 'https://images.unsplash.com/photo-1626700051175-6518c4793f4f?auto=format&fit=crop&w=400&q=80',
                                    'Gado-Gado' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=400&q=80',
                                    'Tempe Mendoan' => 'https://images.unsplash.com/photo-1567982047351-76b6f93e38ee?auto=format&fit=crop&w=400&q=80',
                                    'Pisang Goreng Keju' => 'https://images.unsplash.com/photo-1590080875515-8a3a8dc5735e?auto=format&fit=crop&w=400&q=80',
                                    'Es Teh Manis' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=400&q=80',
                                    'Es Jeruk Peras' => 'https://images.unsplash.com/photo-1621506289937-a8e4df240d0b?auto=format&fit=crop&w=400&q=80',
                                    'Jus Alpukat' => 'https://images.unsplash.com/photo-1553530666-ba11a7da3888?auto=format&fit=crop&w=400&q=80',
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
                                    <span class="px-2.5 py-0.5 bg-gray-500 text-white text-[9px] font-extrabold rounded-full uppercase tracking-wider">
                                        Sold Out
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 bg-white text-gray-800 text-[9px] font-extrabold rounded-full shadow-sm">
                                        {{ $menu->stock }} left
                                    </span>
                                    @if($menu->stock <= 10)
                                        <span class="px-2.5 py-0.5 bg-red-500 text-white text-[9px] font-extrabold rounded-full uppercase tracking-wider animate-pulse">
                                            Hot
                                        </span>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 flex-1 flex flex-col justify-between space-y-4 bg-gradient-to-b from-white to-[#fff8f6]">
                            <div>
                                <h4 class="font-bold text-[#231914] leading-tight font-headline-md">
                                    {{ $menu->name }}
                                </h4>
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2">
                                    {{ $menu->description ?? 'Savory and delicious campus favorite.' }}
                                </p>
                            </div>

                            <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                <span class="text-[#9b4500] font-extrabold text-base">
                                    ${{ number_format($menu->price / 1000, 2) }}
                                </span>
                                
                                @if($menu->stock > 0)
                                    <button 
                                        wire:click="openCustomize({{ $menu->id }})"
                                        class="w-8 h-8 bg-[#8e4e14] hover:bg-[#9b4500] text-white flex items-center justify-center rounded-full transition-all shadow-md active:scale-90"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                @else
                                    <div class="w-8 h-8 bg-gray-100 text-gray-400 flex items-center justify-center rounded-full">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Sticky Cart Bar (Slide 2 Bottom) -->
        @php
            $totalItems = array_sum($cart);
        @endphp
        @if($totalItems > 0)
            <div class="fixed bottom-6 right-6 z-50">
                <button 
                    wire:click="goToCheckout"
                    class="flex items-center gap-3 px-6 py-4 bg-[#8e4e14] hover:bg-[#9b4500] text-white font-extrabold rounded-full transition-all shadow-xl hover:scale-105 active:scale-95"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="text-sm">View Cart (${{ number_format($this->totalPrice / 1000, 2) }})</span>
                </button>
            </div>
        @endif

    @elseif($step === 'customize')
        <!-- ================================================================= -->
        <!-- STEP 2: CUSTOMIZE VIEW (Slide 5)                                  -->
        <!-- ================================================================= -->
        @php
            $menu = $menus->firstWhere('id', $selectedMenuId);
        @endphp
        @if($menu)
            <div class="space-y-6 max-w-4xl mx-auto">
                <!-- Back Link -->
                <button wire:click="closeCustomize" class="flex items-center gap-2 text-xs font-bold text-[#9b4500] hover:underline">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Menu
                </button>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-stretch">
                    <!-- Left: Large Image Card -->
                    <div class="relative rounded-[32px] overflow-hidden shadow-md min-h-[300px] md:h-auto bg-orange-50 border border-[#feeae0]">
                        @php
                            $imgUrl = match($menu->name) {
                                'Nasi Goreng Spesial' => 'https://images.unsplash.com/photo-1512058564366-18510be2db19?auto=format&fit=crop&w=600&q=80',
                                'Mie Ayam Bakso' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?auto=format&fit=crop&w=600&q=80',
                                'Soto Ayam Lamongan' => 'https://images.unsplash.com/photo-1547928576-a4a33237eceb?auto=format&fit=crop&w=600&q=80',
                                'Nasi Padang Lengkap' => 'https://images.unsplash.com/photo-1626700051175-6518c4793f4f?auto=format&fit=crop&w=600&q=80',
                                'Gado-Gado' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=600&q=80',
                                'Tempe Mendoan' => 'https://images.unsplash.com/photo-1567982047351-76b6f93e38ee?auto=format&fit=crop&w=600&q=80',
                                'Pisang Goreng Keju' => 'https://images.unsplash.com/photo-1590080875515-8a3a8dc5735e?auto=format&fit=crop&w=600&q=80',
                                'Es Teh Manis' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=600&q=80',
                                'Es Jeruk Peras' => 'https://images.unsplash.com/photo-1621506289937-a8e4df240d0b?auto=format&fit=crop&w=600&q=80',
                                'Jus Alpukat' => 'https://images.unsplash.com/photo-1553530666-ba11a7da3888?auto=format&fit=crop&w=600&q=80',
                                default => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80'
                            };
                        @endphp
                        <img 
                            src="{{ $menu->photo_path ? asset('storage/' . $menu->photo_path) : $imgUrl }}" 
                            alt="{{ $menu->name }}" 
                            class="w-full h-full object-cover"
                        />
                        <div class="absolute top-4 left-4 flex gap-2">
                            <span class="px-3 py-1 bg-amber-500 text-white text-[10px] font-extrabold rounded-full">Popular</span>
                            <span class="px-3 py-1 bg-[#2e7d32] text-white text-[10px] font-extrabold rounded-full">Indonesian Favorite</span>
                        </div>
                    </div>

                    <!-- Right: Customization Details -->
                    <div class="bg-white border border-[#feeae0] rounded-[32px] p-6 md:p-8 flex flex-col justify-between space-y-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-start">
                                <h1 class="text-2xl md:text-3xl font-extrabold text-[#231914] tracking-tight leading-none">{{ $menu->name }}</h1>
                                <span class="text-xl md:text-2xl font-extrabold text-[#9b4500]">${{ number_format($menu->price / 1000, 2) }}</span>
                            </div>
                            
                            <p class="text-xs md:text-sm text-gray-500 leading-relaxed">
                                {{ $menu->description ?? 'Savory and delicious local flavor crafted with high-quality ingredients.' }}
                            </p>

                            <!-- Meta Info -->
                            <div class="flex gap-4 text-xs font-semibold text-gray-500 pt-2">
                                <span class="inline-flex items-center gap-1.5">
                                    ⏱ {{ $menu->cooking_time_minutes }} mins cook time
                                </span>
                                <span class="inline-flex items-center gap-1.5">
                                    🔥 {{ 250 + (crc32($menu->name) % 30) * 10 }} kcal
                                </span>
                            </div>

                            <!-- Special notes -->
                            <div class="space-y-2 pt-4">
                                <label class="block text-xs font-bold text-[#231914] uppercase tracking-wider">Special Notes</label>
                                <textarea 
                                    wire:model="customizeNote"
                                    placeholder="E.g., No spicy, extra sauce, less sugar..."
                                    rows="3" 
                                    class="block w-full border border-[#f2dfd5] focus:border-[#ffab69] focus:ring-[#ffab69] bg-[#fff8f6] rounded-2xl text-xs placeholder-gray-400"
                                ></textarea>
                            </div>
                        </div>

                        <!-- Action Bar -->
                        <div class="flex items-center justify-between pt-6 border-t border-[#feeae0] gap-4">
                            <!-- Qty selector horizontal oval -->
                            <div class="flex items-center bg-[#FAF3EB] border border-[#f2dfd5] rounded-full p-1">
                                <button 
                                    wire:click="decrementCustomize" 
                                    class="w-9 h-9 flex items-center justify-center bg-white border border-[#f2dfd5] hover:border-[#ffab69] text-[#9b4500] font-extrabold rounded-full transition-all"
                                >
                                    -
                                </button>
                                <span class="px-5 text-sm font-extrabold text-[#231914]">{{ $customizeQuantity }}</span>
                                <button 
                                    wire:click="incrementCustomize" 
                                    class="w-9 h-9 flex items-center justify-center bg-white border border-[#f2dfd5] hover:border-[#ffab69] text-[#9b4500] font-extrabold rounded-full transition-all"
                                >
                                    +
                                </button>
                            </div>

                            <!-- Big orange add button -->
                            <button 
                                wire:click="addCustomizeToCart"
                                class="flex-1 py-3.5 bg-[#8e4e14] hover:bg-[#9b4500] text-white text-xs font-extrabold rounded-full transition-all shadow-md active:scale-95"
                            >
                                Add to Cart (${{ number_format(($menu->price * $customizeQuantity) / 1000, 2) }})
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    @elseif($step === 'checkout')
        <!-- ================================================================= -->
        <!-- STEP 3: CHECKOUT / CART VIEW (Slide 3)                            -->
        <!-- ================================================================= -->
        <div class="space-y-6 max-w-4xl mx-auto">
            <!-- Back Link -->
            <button wire:click="goToMenu" class="flex items-center gap-2 text-xs font-bold text-[#9b4500] hover:underline">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Menu
            </button>

            <!-- Page Title -->
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-[#231914] tracking-tight leading-none">Checkout</h1>
                <p class="text-xs text-gray-500 mt-1.5">Review your items and complete your order.</p>
            </div>

            @if($stockError)
                <div class="p-4 bg-red-100 border-l-4 border-red-500 text-red-700 text-xs rounded-r-xl">
                    {{ $stockError }}
                </div>
            @endif

            <!-- Layout Split -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                <!-- Left: Your Order Panel -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white border border-[#feeae0] rounded-[32px] p-6 space-y-6 shadow-sm">
                        <h3 class="text-lg font-bold text-[#231914] pb-3 border-b border-[#feeae0]">Your Order</h3>

                        <div class="space-y-4">
                            @foreach($cart as $menuId => $qty)
                                @php
                                    $menu = $menus->firstWhere('id', $menuId);
                                @endphp
                                @if($menu)
                                    @php
                                        $imgUrl = match($menu->name) {
                                            'Nasi Goreng Spesial' => 'https://images.unsplash.com/photo-1512058564366-18510be2db19?auto=format&fit=crop&w=150&q=80',
                                            'Mie Ayam Bakso' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?auto=format&fit=crop&w=150&q=80',
                                            'Soto Ayam Lamongan' => 'https://images.unsplash.com/photo-1547928576-a4a33237eceb?auto=format&fit=crop&w=150&q=80',
                                            'Nasi Padang Lengkap' => 'https://images.unsplash.com/photo-1626700051175-6518c4793f4f?auto=format&fit=crop&w=150&q=80',
                                            'Gado-Gado' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=150&q=80',
                                            'Tempe Mendoan' => 'https://images.unsplash.com/photo-1567982047351-76b6f93e38ee?auto=format&fit=crop&w=150&q=80',
                                            'Pisang Goreng Keju' => 'https://images.unsplash.com/photo-1590080875515-8a3a8dc5735e?auto=format&fit=crop&w=150&q=80',
                                            'Es Teh Manis' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=150&q=80',
                                            'Es Jeruk Peras' => 'https://images.unsplash.com/photo-1621506289937-a8e4df240d0b?auto=format&fit=crop&w=150&q=80',
                                            'Jus Alpukat' => 'https://images.unsplash.com/photo-1553530666-ba11a7da3888?auto=format&fit=crop&w=150&q=80',
                                            default => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=150&q=80'
                                        };
                                    @endphp
                                    <div class="flex items-start gap-4 p-4 rounded-2xl border border-[#feeae0] bg-[#fff8f6]" wire:key="cart-item-{{ $menu->id }}">
                                        <!-- Image -->
                                        <img src="{{ $menu->photo_path ? asset('storage/' . $menu->photo_path) : $imgUrl }}" 
                                             alt="{{ $menu->name }}" 
                                             class="w-16 h-16 rounded-xl object-cover border border-[#f2dfd5] bg-orange-100" />
                                        
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
                                                <span class="font-extrabold text-[#9b4500] text-sm md:text-base">${{ number_format(($menu->price * $qty) / 1000, 2) }}</span>
                                            </div>

                                            <div class="flex justify-between items-center pt-2">
                                                <!-- Quantity selector in Checkout -->
                                                <div class="flex items-center bg-white border border-[#f2dfd5] rounded-full p-0.5">
                                                    <button 
                                                        wire:click="decrementQty({{ $menu->id }})" 
                                                        class="w-7 h-7 flex items-center justify-center hover:bg-orange-50 text-[#9b4500] font-extrabold rounded-full"
                                                    >
                                                        -
                                                    </button>
                                                    <span class="px-3.5 text-xs font-extrabold text-[#231914]">{{ $qty }}</span>
                                                    <button 
                                                        wire:click="incrementQty({{ $menu->id }})" 
                                                        class="w-7 h-7 flex items-center justify-center hover:bg-orange-50 text-[#9b4500] font-extrabold rounded-full"
                                                    >
                                                        +
                                                    </button>
                                                </div>

                                                <!-- Delete link -->
                                                <button 
                                                    wire:click="removeFromCart({{ $menu->id }})"
                                                    class="text-xs font-semibold text-red-600 hover:text-red-700 flex items-center gap-1"
                                                >
                                                    🗑 Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <!-- Notes text -->
                        <div class="space-y-2 pt-4 border-t border-[#feeae0]">
                            <label class="block text-xs font-bold text-[#231914] uppercase tracking-wider">Add Order Notes</label>
                            <textarea 
                                wire:model="note"
                                placeholder="Any special requests? (E.g., cutlery needed, allergies)..."
                                rows="3" 
                                class="block w-full border border-[#f2dfd5] focus:border-[#ffab69] focus:ring-[#ffab69] bg-[#fff8f6] rounded-2xl text-xs placeholder-gray-400"
                            ></textarea>
                        </div>
                    </div>
                </div>

                <!-- Right: Wait & Summary -->
                <div class="space-y-6">
                    <!-- Wait Estimator (Slide 3) -->
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
                                <span class="block text-[10px] font-bold text-gray-500 uppercase tracking-wide">Estimated Wait</span>
                                <span class="text-lg font-extrabold text-gray-800 leading-none">{{ $approxWait - 2 }} - {{ $approxWait + 2 }} min</span>
                            </div>
                        </div>
                        
                        <!-- Simple mockup loader indicator -->
                        <div class="w-10 h-10 rounded-full border-4 border-orange-200 border-t-[#8e4e14] animate-spin"></div>
                    </div>

                    <!-- Checkout Summary -->
                    @php
                        $subTotal = $this->totalPrice;
                        $tax = (int) round($subTotal * 0.08);
                        $campusFee = 500;
                        $grandTotal = $subTotal + $tax + $campusFee;
                    @endphp
                    <div class="bg-white border border-[#feeae0] rounded-3xl p-6 space-y-4 shadow-sm flex flex-col">
                        <h3 class="text-lg font-bold text-[#231914] pb-2 border-b border-[#feeae0]">Summary</h3>

                        <div class="space-y-2 text-xs font-semibold text-gray-500">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span class="text-[#231914] font-bold">${{ number_format($subTotal / 1000, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Tax (8%)</span>
                                <span class="text-[#231914] font-bold">${{ number_format($tax / 1000, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Campus Fee</span>
                                <span class="text-[#231914] font-bold">${{ number_format($campusFee / 1000, 2) }}</span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-[#feeae0] flex justify-between items-baseline">
                            <span class="text-sm font-bold text-gray-700">Total</span>
                            <span class="text-2xl md:text-3xl font-extrabold text-[#9b4500]">${{ number_format($grandTotal / 1000, 2) }}</span>
                        </div>

                        <button 
                            wire:click="checkout"
                            class="w-full py-3.5 mt-4 bg-[#8e4e14] hover:bg-[#9b4500] text-white text-xs font-extrabold rounded-full transition-all shadow-md active:scale-95 flex items-center justify-center gap-2"
                        >
                            🔒 Place Order
                        </button>
                        <span class="block text-center text-[9px] text-gray-400 mt-2">Payments are secure and encrypted.</span>
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
                    <h3 class="text-xl font-extrabold text-[#231914]">Simulate Payment</h3>
                    <p class="text-xs text-gray-500">This is a sandbox environment. Simply click 'Pay Sandbox' to simulate order approval.</p>
                </div>

                <div class="p-4 bg-[#FAF3EB] rounded-2xl border border-[#f2dfd5] space-y-2 text-xs font-semibold text-gray-600">
                    <div class="flex justify-between">
                        <span>Grand Total</span>
                        <span class="text-[#9b4500] font-bold">${{ number_format($this->totalPrice / 1000 * 1.08 + 0.50, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Payment Method</span>
                        <span class="text-gray-800 font-bold">Dummy Wallet</span>
                    </div>
                </div>

                <div class="flex gap-4 pt-2">
                    <button 
                        wire:click="cancelPaymentModal"
                        class="flex-1 py-3 bg-[#FAF3EB] border border-[#f2dfd5] text-xs font-bold text-gray-600 rounded-full transition-all"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="simulatePayment"
                        class="flex-1 py-3 bg-[#2e7d32] hover:bg-[#1b5e20] text-xs font-extrabold text-white rounded-full transition-all shadow-md active:scale-95"
                    >
                        Pay Sandbox
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
