<div class="flex flex-col min-h-screen bg-gray-50 md:flex-row">
    <aside class="w-full bg-white border-r border-gray-200 md:w-64 md:min-h-screen">
        <div class="flex items-center justify-between p-6 md:block">
            <h1 class="text-xl font-bold text-orange-600">Kantin Manager</h1>
        </div>
        <nav class="hidden px-4 pb-6 space-y-2 md:block">
            <a href="#" class="flex items-center px-4 py-3 text-orange-700 bg-orange-100 rounded-lg">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard Live
            </a>
            <a href="#" class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Laporan Penjualan
            </a>
        </nav>
    </aside>

    <main class="flex-1 p-6">
        <div class="flex flex-col items-start justify-between mb-8 space-y-4 md:flex-row md:items-center md:space-y-0">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Live Order Feed</h2>
                <p class="text-sm text-gray-500">Pesanan masuk diperbarui secara otomatis (Real-time)</p>
            </div>
            <div class="flex items-center px-4 py-2 bg-white border border-gray-200 rounded-full shadow-sm">
                <span class="relative flex w-3 h-3 mr-3">
                  <span class="absolute inline-flex w-full h-full bg-green-400 rounded-full opacity-75 animate-ping"></span>
                  <span class="relative inline-flex w-3 h-3 bg-green-500 rounded-full"></span>
                </span>
                <span class="text-sm font-medium text-gray-700">{{ count($orders) }} Pesanan Aktif</span>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            @forelse($orders as $order)
                <div class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-xl" wire:key="order-{{ $order->id }}">
                    <div class="flex items-center justify-between p-5 border-b border-gray-100">
                        <div>
                            <span class="text-lg font-bold text-gray-800">#{{ $order->id }}</span>
                            <span class="text-sm text-gray-500 ml-2">{{ $order->created_at->format('H:i') }} WIB</span>
                        </div>
                        
                        @if($order->status === 'diterima')
                            <span class="px-3 py-1 text-xs font-semibold text-orange-700 bg-orange-100 rounded-full">Pesanan Baru</span>
                        @elseif($order->status === 'diproses')
                            <span class="px-3 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">Sedang Dimasak</span>
                        @elseif($order->status === 'siap_diambil')
                            <span class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Menunggu Diambil</span>
                        @endif
                    </div>

                    <div class="p-5 flex-1">
                        <div class="mb-4">
                            <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Pembeli</p>
                            <p class="text-sm font-medium text-gray-800">{{ $order->buyer->email ?? 'Mahasiswa' }}</p>
                        </div>
                        
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-2">Item Pesanan</p>
                        <ul class="space-y-3">
                            @foreach($order->items as $item)
                                <li class="flex items-start justify-between">
                                    <div class="flex items-start">
                                        <span class="px-2 py-0.5 mr-3 text-xs font-bold text-gray-600 bg-gray-100 rounded">{{ $item->quantity }}x</span>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">{{ $item->menu_name_snapshot ?? $item->menu->name ?? 'Item' }}</p>
                                            @if($item->notes)
                                                <p class="text-xs text-gray-500 mt-0.5"><span class="font-semibold">Catatan:</span> {{ $item->notes }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="p-5 bg-gray-50 rounded-b-xl border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="w-full sm:w-auto">
                            <p class="text-xs text-gray-500">Total Pembayaran</p>
                            <p class="text-lg font-bold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        </div>

                        <div class="flex w-full gap-2 sm:w-auto">
                            @if(in_array($order->status, ['diterima', 'diproses']))
                                <button 
                                    wire:click="reject({{ $order->id }})" 
                                    wire:loading.attr="disabled"
                                    class="px-4 py-2 text-sm font-medium text-red-600 bg-white border border-red-200 rounded-lg hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 disabled:opacity-50 transition-colors w-full sm:w-auto"
                                >
                                    <span wire:loading.remove wire:target="reject({{ $order->id }})">Tolak</span>
                                    <span wire:loading wire:target="reject({{ $order->id }})">...</span>
                                </button>
                            @endif

                            @if($order->status === 'diterima')
                                <button 
                                    wire:click="process({{ $order->id }})" 
                                    wire:loading.attr="disabled"
                                    class="px-5 py-2 text-sm font-medium text-white bg-orange-600 rounded-lg shadow-sm hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-1 disabled:opacity-50 transition-colors w-full sm:w-auto"
                                >
                                    <span wire:loading.remove wire:target="process({{ $order->id }})">Proses Pesanan</span>
                                    <span wire:loading wire:target="process({{ $order->id }})">Memproses...</span>
                                </button>
                            @elseif($order->status === 'diproses')
                                <button 
                                    wire:click="markReady({{ $order->id }})" 
                                    wire:loading.attr="disabled"
                                    class="px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 disabled:opacity-50 transition-colors w-full sm:w-auto"
                                >
                                    <span wire:loading.remove wire:target="markReady({{ $order->id }})">Siap Diambil</span>
                                    <span wire:loading wire:target="markReady({{ $order->id }})">Memproses...</span>
                                </button>
                            @elseif($order->status === 'siap_diambil')
                                <button disabled class="px-5 py-2 text-sm font-medium text-gray-500 bg-gray-200 rounded-lg w-full sm:w-auto cursor-not-allowed">
                                    Menunggu Diambil Mhs.
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center col-span-1 py-16 bg-white border border-gray-200 border-dashed rounded-xl xl:col-span-2">
                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <h3 class="text-lg font-medium text-gray-900">Belum Ada Pesanan Aktif</h3>
                    <p class="mt-1 text-sm text-gray-500">Pesanan baru dari mahasiswa akan otomatis muncul di sini.</p>
                </div>
            @endforelse
        </div>
    </main>
</div>
<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
</div>
