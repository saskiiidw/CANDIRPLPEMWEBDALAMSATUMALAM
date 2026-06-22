<div class="max-w-xl mx-auto bg-white border border-[#feeae0] rounded-3xl p-8 shadow-md space-y-8">
    <!-- Header -->
    <div class="text-center space-y-2">
        <span class="text-xs font-bold text-[#897266] uppercase tracking-widest">Order Status</span>
        <h1 class="text-3xl font-extrabold text-[#231914] font-display-lg">Track Your Order</h1>
        <p class="text-sm font-bold text-[#9b4500]">Order #{{ $order->id }}</p>
    </div>

    <!-- Notification Alert -->
    @if($notificationMessage)
        <div class="p-4 bg-orange-50 border border-[#ffab69]/50 text-amber-950 text-sm rounded-2xl flex items-start gap-3">
            <svg class="w-5 h-5 text-[#9b4500] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ $notificationMessage }}</span>
        </div>
    @endif

    <!-- Timeline Progress -->
    @php
        $status = $order->status;
        $steps = [
            'diterima' => ['label' => 'Received', 'desc' => 'Waiting for canteen confirmation', 'completed' => in_array($status, ['diterima', 'diproses', 'siap_diambil', 'selesai'])],
            'diproses' => ['label' => 'Preparing', 'desc' => 'Your food is cooking', 'completed' => in_array($status, ['diproses', 'siap_diambil', 'selesai'])],
            'siap_diambil' => ['label' => 'Ready', 'desc' => 'Ready for pick up at canteen', 'completed' => in_array($status, ['siap_diambil', 'selesai'])],
            'selesai' => ['label' => 'Completed', 'desc' => 'Picked up successfully', 'completed' => $status === 'selesai']
        ];
    @endphp

    @if($status === 'ditolak' || $status === 'dibatalkan')
        <!-- Terminated Status Block -->
        <div class="p-6 bg-red-50 border border-red-200 text-center rounded-2xl space-y-2">
            <div class="w-12 h-12 bg-red-100 text-red-600 mx-auto rounded-full flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </div>
            <h3 class="text-lg font-bold text-red-800">Order {{ ucfirst($status) }}</h3>
            <p class="text-xs text-red-700">
                @if($status === 'ditolak')
                    Canteen has rejected your order. Refund is initiated to your wallet.
                @else
                    You have cancelled this order.
                @endif
            </p>
        </div>
    @else
        <!-- Active Timeline -->
        <div class="relative pl-8 space-y-8 before:absolute before:left-3 before:top-2 before:bottom-2 before:w-0.5 before:bg-[#f2dfd5]">
            @foreach($steps as $key => $stepData)
                <div class="relative">
                    <!-- Marker -->
                    <div class="absolute -left-[27px] top-1.5 w-[14px] h-[14px] rounded-full border-2 transition-all {{ $stepData['completed'] ? 'bg-[#9b4500] border-[#9b4500] scale-110 shadow-sm' : 'bg-white border-[#f2dfd5]' }}"></div>
                    <!-- Label -->
                    <div class="space-y-0.5">
                        <h4 class="text-sm font-bold {{ $stepData['completed'] ? 'text-[#231914]' : 'text-[#897266]' }}">{{ $stepData['label'] }}</h4>
                        <p class="text-xs text-[#897266]">{{ $stepData['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Order Items Summary Card -->
    <div class="border border-[#feeae0] bg-[#fff8f6] rounded-2xl p-5 space-y-4">
        <h3 class="font-bold text-sm text-[#231914] uppercase tracking-wide">Summary Detail</h3>
        <ul class="space-y-2.5">
            @foreach($order->items as $item)
                <li class="flex justify-between items-baseline text-sm text-[#231914] font-medium">
                    <span class="text-[#897266]">{{ $item->quantity }}x <span class="text-[#231914] font-bold">{{ $item->menu_name_snapshot }}</span></span>
                    <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </li>
            @endforeach
        </ul>
        <div class="pt-3 border-t border-[#feeae0] flex justify-between items-baseline font-bold">
            <span class="text-sm text-[#231914]">Total Price</span>
            <span class="text-xl text-[#9b4500]">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Confirm Receipt Button -->
    @if($status === 'siap_diambil')
        <div class="pt-4 text-center">
            <button 
                wire:click="confirmReceived"
                class="w-full py-4 bg-[#2e7d32] hover:bg-[#1b5e20] text-white font-extrabold rounded-full transition-all shadow-md active:scale-95 flex items-center justify-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Confirm Order Received
            </button>
            <span class="block text-[10px] text-[#897266] mt-2">Only click this after physically receiving your meal.</span>
        </div>
    @endif
</div>
