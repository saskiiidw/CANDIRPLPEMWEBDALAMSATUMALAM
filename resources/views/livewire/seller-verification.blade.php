<div class="flex gap-8">
    <!-- Top bar search & profile (fits nicely in the top navbar space) -->
    @push('header-left')
        <div class="relative w-80">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="w-5 h-5 text-[#897266]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </span>
            <input type="text" 
                   wire:model.live="search"
                   placeholder="Cari penjual..." 
                   class="w-full pl-10 pr-4 py-2 bg-white border border-[#f2dfd5] rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-[#9b4500] focus:border-transparent text-[#231914] placeholder-[#897266] transition">
        </div>
    @endpush

    <!-- Left Panel: Table of Sellers -->
    <div class="flex-1">
        <div class="mb-6">
            <h1 class="text-3xl font-extrabold text-[#331200] font-headline-md mb-1">Menunggu Verifikasi</h1>
            <p class="text-sm text-[#897266] font-body-md">Tinjau dan setujui pendaftaran penjual kantin baru.</p>
        </div>

        @if (session()->has('message'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl text-sm font-semibold flex items-center">
                <svg class="w-5 h-5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('message') }}
            </div>
        @endif

        <!-- Table Container -->
        <div class="bg-white rounded-3xl border border-[#f2dfd5] overflow-hidden shadow-sm">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#fff1eb] border-b border-[#f2dfd5]">
                        <th class="p-5 text-xs font-semibold uppercase tracking-wider text-[#897266] font-sans">Nama Penjual</th>
                        <th class="p-5 text-xs font-semibold uppercase tracking-wider text-[#897266] font-sans">Nama Kantin</th>
                        <th class="p-5 text-xs font-semibold uppercase tracking-wider text-[#897266] font-sans">Tanggal Mendaftar</th>
                        <th class="p-5 text-xs font-semibold uppercase tracking-wider text-[#897266] font-sans">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f2dfd5]">
                    @forelse($sellers as $seller)
                        <tr wire:click="selectSeller({{ $seller->id }})" 
                            class="cursor-pointer transition duration-150 hover:bg-[#fff8f6] {{ $selectedSellerId === $seller->id ? 'bg-[#feeae0]' : '' }}">
                            <td class="p-5 font-semibold text-[#331200] font-sans text-sm">{{ $seller->name }}</td>
                            <td class="p-5 text-sm text-[#897266]">{{ $seller->store_name }}</td>
                            <td class="p-5 text-sm text-[#897266]">{{ $seller->created_at->format('M d, Y') }}</td>
                            <td class="p-5">
                                @if($seller->is_verified)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                        Terverifikasi
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#fff1eb] text-[#8e4e14] border border-[#f2dfd5]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#f49b65] mr-1.5"></span>
                                        Menunggu
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-10 text-center text-[#897266]">Tidak ada penjual yang sesuai kriteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $sellers->links() }}
        </div>
    </div>

    <!-- Right Panel: Detail Card -->
    <div class="w-96 shrink-0">
        @if($selectedSeller)
            <div class="bg-white rounded-3xl border border-[#f2dfd5] p-6 shadow-sm flex flex-col h-full justify-between min-h-[500px]">
                <div>
                    <!-- Detail Header Area -->
                    <div class="flex justify-between items-start mb-6">
                        @if($selectedSeller->is_verified)
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                                Terverifikasi
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#fff1eb] text-[#8e4e14] border border-[#f2dfd5]">
                                Menunggu Tinjauan
                            </span>
                        @endif

                        <!-- User Photo -->
                        <div class="relative">
                            <img class="w-16 h-16 rounded-full object-cover border border-[#f2dfd5]" 
                                 src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=150&q=80" 
                                 alt="Avatar">
                            <span class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-green-500 rounded-full border-2 border-white"></span>
                        </div>
                    </div>

                    <!-- Seller Title/Canteen -->
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-[#331200] leading-tight font-headline-md">{{ $selectedSeller->name }}</h3>
                        <p class="text-sm font-semibold text-[#8e4e14] mt-1">{{ $selectedSeller->store_name }}</p>
                    </div>

                    <!-- Contact Details Box -->
                    <div class="bg-[#fff1eb] rounded-2xl p-4 border border-[#f2dfd5] mb-6">
                        <div class="flex items-center space-x-2 text-xs font-semibold text-[#8e4e14] mb-3 uppercase tracking-wider">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>Detail Kontak</span>
                        </div>
                        <div class="space-y-2 text-sm text-[#231914]">
                            <div class="flex items-center space-x-3">
                                <svg class="w-4 h-4 text-[#897266]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="break-all">{{ $selectedSeller->email }}</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <svg class="w-4 h-4 text-[#897266]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span>{{ $selectedSeller->phone ?? '+1 (555) 123-4567' }}</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Detail Panel Action Buttons (Reject & Approve) -->
                @if(!$selectedSeller->is_verified)
                    <div class="flex space-x-3 pt-6 border-t border-[#f2dfd5] mt-auto">
                        <button wire:click="reject({{ $selectedSeller->id }})" 
                                class="flex-1 py-3 px-4 rounded-xl border border-red-200 text-red-500 font-semibold hover:bg-red-50 transition text-sm">
                            Tolak
                        </button>
                        <button wire:click="approve({{ $selectedSeller->id }})" 
                                class="flex-1 py-3 px-4 rounded-xl bg-[#8e4e14] text-white font-semibold hover:bg-[#9b4500] transition text-sm flex items-center justify-center space-x-1.5 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Setujui</span>
                        </button>
                    </div>
                @else
                    <div class="text-center text-xs font-semibold text-green-700 bg-green-50 py-3 rounded-xl border border-green-150">
                        Penjual ini sudah disetujui dan diverifikasi.
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white rounded-3xl border border-[#f2dfd5] p-6 shadow-sm text-center py-20 text-[#897266]">
                Pilih penjual untuk melihat detail mereka.
            </div>
        @endif
    </div>
</div>
