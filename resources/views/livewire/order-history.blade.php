<div class="space-y-8 max-w-4xl mx-auto">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-extrabold text-[#231914] tracking-tight leading-none">Order History</h1>
        <p class="text-xs md:text-sm text-gray-500 mt-2">Review your past meals and reorder your favorites.</p>
    </div>

    <!-- Filters Row -->
    <div class="flex flex-col md:flex-row gap-4 items-center justify-between bg-white border border-[#feeae0] p-5 rounded-3xl shadow-sm">
        <!-- Search bar -->
        <div class="relative w-full md:w-80">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search"
                placeholder="Search orders..." 
                class="block w-full pl-9 pr-4 py-2.5 bg-[#FAF3EB] border border-[#f2dfd5] focus:border-[#ffab69] focus:ring-[#ffab69] rounded-2xl text-xs placeholder-gray-500"
            />
        </div>

        <!-- Date Filters -->
        <div class="flex items-center gap-3 w-full md:w-auto">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider hidden sm:inline">Date Range</span>
            <div class="flex items-center gap-2 bg-[#FAF3EB] border border-[#f2dfd5] rounded-2xl p-1 flex-1 md:flex-none">
                <input 
                    type="date" 
                    wire:model.live="startDate"
                    class="bg-transparent border-0 focus:ring-0 text-xs font-bold text-gray-700 p-1 w-full sm:w-auto"
                />
                <span class="text-gray-400 text-xs font-bold">-</span>
                <input 
                    type="date" 
                    wire:model.live="endDate"
                    class="bg-transparent border-0 focus:ring-0 text-xs font-bold text-gray-700 p-1 w-full sm:w-auto"
                />
            </div>
        </div>
    </div>

    <!-- Orders List (Slide 4) -->
    <div class="space-y-4">
        @forelse($orders as $order)
            @php
                $canteenName = $order->seller->store_name ?? 'Kantin Mas Eddy';
                $itemsSummary = $order->items->map(fn($item) => "{$item->quantity}x {$item->menu_name_snapshot}")->implode(', ');
            @endphp
            <div class="bg-white border border-[#feeae0] hover:border-[#ffab69]/50 rounded-3xl p-5 shadow-sm hover:shadow-md transition-all flex flex-col md:flex-row justify-between items-start md:items-center gap-6" wire:key="history-order-{{ $order->id }}">
                <div class="flex items-start gap-4">
                    <!-- Food Icon Frame -->
                    <div class="w-14 h-14 rounded-2xl bg-orange-50 flex-shrink-0 overflow-hidden border border-[#feeae0] flex items-center justify-center text-2xl shadow-inner">
                        🍱
                    </div>
                    
                    <div class="space-y-1">
                        <div class="flex flex-wrap items-center gap-2.5">
                            <h3 class="text-base font-bold text-[#231914]">{{ $canteenName }}</h3>
                            
                            <!-- Status Badges -->
                            @if($order->status === 'selesai')
                                <span class="px-2.5 py-0.5 bg-green-550/10 text-green-700 text-[9px] font-extrabold rounded-full uppercase tracking-wider">
                                    ✓ Completed
                                </span>
                            @elseif($order->status === 'dibatalkan' || $order->status === 'ditolak')
                                <span class="px-2.5 py-0.5 bg-red-50 text-red-700 text-[9px] font-extrabold rounded-full uppercase tracking-wider">
                                    ✗ Cancelled
                                </span>
                            @elseif($order->status === 'siap_diambil')
                                <span class="px-2.5 py-0.5 bg-blue-50 text-blue-700 text-[9px] font-extrabold rounded-full uppercase tracking-wider animate-pulse">
                                    🔔 Ready to Pick Up
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 bg-amber-50 text-amber-700 text-[9px] font-extrabold rounded-full uppercase tracking-wider">
                                    ● {{ str_replace('_', ' ', $order->status) }}
                                </span>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="text-[10px] text-gray-400 font-semibold flex items-center gap-1">
                            <span>📅 {{ $order->created_at->format('M d, Y • h:i A') }}</span>
                        </div>
                        <p class="text-xs text-gray-600 font-semibold max-w-md line-clamp-1">
                            {{ $itemsSummary }}
                        </p>
                    </div>
                </div>

                <!-- Price and Buttons -->
                <div class="flex md:flex-col items-end justify-between w-full md:w-auto pt-3 md:pt-0 border-t md:border-t-0 border-[#feeae0] gap-4">
                    <span class="text-xl font-extrabold text-[#9b4500]">${{ number_format($order->total_price / 1000, 2) }}</span>
                    
                    <div class="flex items-center gap-2">
                        @if($order->status === 'selesai')
                            <button class="px-3.5 py-1.5 text-xs font-bold text-gray-500 hover:text-[#9b4500] bg-[#FAF3EB] border border-[#f2dfd5] rounded-xl transition-all">
                                Receipt
                            </button>
                            <button 
                                wire:click="reorder({{ $order->id }})"
                                class="px-3.5 py-1.5 text-xs font-extrabold text-white bg-[#8e4e14] hover:bg-[#9b4500] rounded-xl transition-all shadow-sm active:scale-95"
                            >
                                Reorder
                            </button>
                        @else
                            <a href="{{ route('orders.track', ['order' => $order->id]) }}" 
                               class="px-3.5 py-1.5 text-xs font-extrabold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-all shadow-sm active:scale-95"
                            >
                                Track Order
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-20 bg-white border border-[#feeae0] border-dashed rounded-3xl shadow-sm">
                <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-4 text-2xl">
                    🗒
                </div>
                <h3 class="text-lg font-bold text-gray-700 mb-1">No Orders Found</h3>
                <p class="text-xs text-gray-400 text-center max-w-xs">You haven't placed any orders matching the selected filters yet.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
        <div class="pt-4 flex justify-center">
            {{ $orders->links() }}
        </div>
    @endif
</div>
