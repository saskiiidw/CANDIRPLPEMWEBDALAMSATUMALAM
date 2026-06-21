<div class="space-y-8 max-w-5xl mx-auto">
    <!-- Header Greeting -->
    <div>
        <h1 class="text-4xl font-extrabold text-[#231914] tracking-tight font-display-lg">
            Hello, {{ explode(' ', auth()->user()->name)[0] }}!
        </h1>
        <p class="text-lg text-[#897266] mt-2 font-body-lg">
            What are you craving today?
        </p>
    </div>

    <!-- Search Bar -->
    <div class="relative max-w-2xl">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-[#897266]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <input 
            type="text" 
            wire:model.live.debounce.300ms="search"
            placeholder="Search for food, canteens, or categories..." 
            class="block w-full pl-12 pr-4 py-4 bg-[#FAF3EB] border border-[#f2dfd5] focus:border-[#ffab69] focus:ring-[#ffab69] rounded-2xl text-base text-[#231914] placeholder-[#897266] transition-all shadow-sm"
        />
    </div>

    <!-- Filter Category Pills -->
    <div class="flex flex-wrap gap-3">
        @foreach(['All', 'Breakfast', 'Lunch', 'Snacks', 'Drinks'] as $category)
            <button 
                wire:click="selectCategory('{{ $category }}')"
                class="px-6 py-3 rounded-full text-sm font-bold tracking-wide transition-all shadow-sm active:scale-95 {{ $selectedCategory === $category ? 'bg-[#9b4500] text-white' : 'bg-white text-[#897266] hover:bg-[#ffab69]/10 border border-[#feeae0] hover:text-[#9b4500]' }}"
            >
                {{ $category }}
            </button>
        @endforeach
    </div>

    <!-- Featured Canteens Section -->
    <div class="space-y-6">
        <div class="flex justify-between items-center border-b border-[#feeae0] pb-3">
            <h2 class="text-2xl font-bold text-[#231914] font-headline-md">Featured Canteens</h2>
            <button class="text-sm font-bold text-[#9b4500] hover:underline transition-all">View All</button>
        </div>

        <!-- Canteens Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse($canteens as $canteen)
                <a href="{{ route('canteen.order', ['seller' => $canteen->id]) }}" 
                   class="group flex flex-col bg-white border border-[#feeae0] hover:border-[#ffab69] rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <!-- Image Area -->
                    <div class="relative h-48 bg-[#f2dfd5] overflow-hidden">
                        <img 
                            src="{{ $canteen->photo_path ? asset('storage/' . $canteen->photo_path) : 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=600&q=80' }}" 
                            alt="{{ $canteen->store_name }}" 
                            class="w-full h-full object-cover transition-all duration-500 group-hover:scale-105"
                        />
                        <!-- Status Tag -->
                        <div class="absolute top-4 left-4">
                            @if($canteen->is_open)
                                <span class="flex items-center gap-1.5 px-3 py-1 bg-[#2e7d32]/90 backdrop-blur-sm text-white text-xs font-extrabold rounded-full">
                                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                                    Open Now
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-600/90 backdrop-blur-sm text-white text-xs font-extrabold rounded-full">
                                    Closed
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Details Area -->
                    <div class="p-6 space-y-4 flex-1 flex flex-col justify-between bg-gradient-to-b from-white to-[#fff8f6]">
                        <div>
                            <h3 class="text-xl font-bold text-[#231914] group-hover:text-[#9b4500] transition-colors font-headline-md">
                                {{ $canteen->store_name }}
                            </h3>
                            <p class="text-sm text-[#897266] mt-1 line-clamp-2">
                                {{ $canteen->description }}
                            </p>
                        </div>

                        <!-- Stats (Rating, Distance) -->
                        <div class="flex items-center justify-between text-sm pt-4 border-t border-[#feeae0] text-[#897266] font-semibold">
                            <!-- Rating -->
                            <div class="flex items-center gap-1">
                                <svg class="w-5 h-5 text-amber-500 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="text-[#231914] font-bold">{{ $canteen->rating }}</span>
                            </div>
                            <!-- Distance -->
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>{{ $canteen->distance }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-2 flex flex-col items-center justify-center py-16 bg-white border border-[#feeae0] border-dashed rounded-3xl">
                    <svg class="w-16 h-16 text-[#897266]/40 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <h3 class="text-xl font-bold text-[#231914]">No Canteens Found</h3>
                    <p class="text-[#897266] text-sm mt-1">Try refining your search terms or selecting another category.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
