<div>
    <!-- Top bar search & profile (fits nicely in the top navbar space) -->
    @push('header-left')
        <div class="relative w-80">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="w-5 h-5 text-[#897266]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </span>
            <input type="text" 
                   placeholder="Search system..." 
                   class="w-full pl-10 pr-4 py-2 bg-white border border-[#f2dfd5] rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-[#9b4500] focus:border-transparent text-[#231914] placeholder-[#897266] transition">
        </div>
    @endpush

    <!-- Header Section -->
    <div class="flex justify-between items-start mb-8">
        <div>
            <h1 class="text-5xl font-extrabold text-[#9b4500] tracking-tight font-display-lg leading-tight mb-2">
                Smart Canteen<br>Admin
            </h1>
        </div>
    </div>

    <!-- Overview Section Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-[#331200] font-headline-md mb-1">Overview</h2>
            <p class="text-sm text-[#897266] font-body-md">Here's what's happening across the campus today.</p>
        </div>
        <div class="flex items-center space-x-2 bg-[#feeae0] px-4 py-2 rounded-full border border-[#f2dfd5] text-[#8e4e14] text-xs font-semibold">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <span>Today, Oct 24</span>
        </div>
    </div>

    <!-- Stats Grid (Adapting exact Layout from Image 1) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        <!-- Card 1: ORDERS TODAY (Large Brown Highlight Card) -->
        <div class="bg-[#8e4e14] text-[#fff8f6] p-8 rounded-3xl relative overflow-hidden shadow-sm flex flex-col justify-between h-48">
            <div class="flex justify-between items-start">
                <div class="w-10 h-10 rounded-full bg-[#9b4500]/60 flex items-center justify-center">
                    <!-- Document Icon -->
                    <svg class="w-5 h-5 text-[#fff8f6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <span class="text-xs bg-[#9b4500]/70 px-3 py-1 rounded-full text-[#fff8f6] font-medium border border-orange-400/20">
                    ↗ +18% from yesterday
                </span>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wider font-semibold opacity-70 mb-1">Orders Today</p>
                <p class="text-4xl font-extrabold tracking-tight font-display-lg">{{ number_format($ordersToday) }}</p>
            </div>
        </div>

        <!-- Card 2: Total Students -->
        <div class="bg-white p-8 rounded-3xl border border-[#f2dfd5] shadow-sm flex flex-col justify-between h-48">
            <div class="flex justify-between items-start">
                <div class="w-10 h-10 rounded-full bg-[#fff1eb] flex items-center justify-center">
                    <!-- Academic Cap Icon -->
                    <svg class="w-5 h-5 text-[#9b4500]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                    </svg>
                </div>
                <!-- Mini up graph indicator -->
                <span class="text-xs text-[#8e4e14] font-semibold">
                    ↑ 2.4%
                </span>
            </div>
            <div>
                <p class="text-xs text-[#897266] uppercase tracking-wider font-semibold mb-1">Total Students</p>
                <p class="text-4xl font-extrabold text-[#331200] tracking-tight font-display-lg">{{ number_format($totalStudents) }}</p>
            </div>
        </div>

        <!-- Card 3: Completed Today -->
        <div class="bg-white p-8 rounded-3xl border border-[#f2dfd5] shadow-sm flex flex-col justify-between h-48">
            <div class="flex justify-between items-start">
                <div class="w-10 h-10 rounded-full bg-[#fff1eb] flex items-center justify-center">
                    <!-- Checkmark Badge Icon -->
                    <svg class="w-5 h-5 text-[#9b4500]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-xs text-[#897266] uppercase tracking-wider font-semibold mb-1">Completed (Today)</p>
                <p class="text-4xl font-extrabold text-[#331200] tracking-tight font-display-lg mb-2">{{ number_format($completedToday) }}</p>
                <!-- Horizontal progress bar matching Image 1 -->
                <div class="w-full bg-[#f2dfd5] rounded-full h-1.5 overflow-hidden">
                    <div class="bg-[#8e4e14] h-full rounded-full" style="width: 85%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Small Stats Cards Grid (Total Sellers, Active Now, Rejected) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <!-- Card 4: Total Sellers -->
        <div class="bg-white p-6 rounded-3xl border border-[#f2dfd5] shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-[#fff1eb] flex items-center justify-center">
                    <!-- Shop Icon -->
                    <svg class="w-6 h-6 text-[#9b4500]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-[#897266] uppercase tracking-wider font-semibold">Total Sellers</p>
                    <p class="text-3xl font-extrabold text-[#331200]">{{ $totalSellers }}</p>
                </div>
            </div>
        </div>

        <!-- Card 5: Active Now -->
        <div class="bg-white p-6 rounded-3xl border border-[#f2dfd5] shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-[#fff1eb] relative flex items-center justify-center">
                    <!-- Terminal/Signal Icon -->
                    <svg class="w-6 h-6 text-[#9b4500]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <!-- Active Green Dot -->
                    <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-green-500 rounded-full border border-white"></span>
                </div>
                <div>
                    <p class="text-xs text-[#897266] uppercase tracking-wider font-semibold">Active Now</p>
                    <p class="text-3xl font-extrabold text-[#331200]">{{ $activeSellers }}</p>
                </div>
            </div>
        </div>

        <!-- Card 6: Rejected/Cancelled Today -->
        <div class="bg-white p-6 rounded-3xl border border-[#f2dfd5] shadow-sm flex items-center space-x-4 border-l-4 border-l-red-400">
            <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center shrink-0">
                <!-- Red Close/Error Icon -->
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs text-[#897266] uppercase tracking-wider font-semibold">Rejected/Cancelled Today</p>
                <div class="flex items-baseline space-x-3">
                    <span class="text-3xl font-extrabold text-[#331200]">{{ $rejectedToday }}</span>
                    <span class="text-xs text-[#897266] font-normal leading-tight">Requires attention. Mainly due to item unavailability.</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Timeline Section (Image 1) -->
    <div class="bg-white rounded-3xl border border-[#f2dfd5] p-8 shadow-sm">
        <div class="flex justify-between items-center mb-8">
            <h3 class="text-xl font-bold text-[#331200] font-headline-md">Recent Activity</h3>
            <a href="{{ route('admin.audit-log') }}" class="text-xs text-[#9b4500] hover:underline font-semibold">View Full Log</a>
        </div>

        <!-- Timeline Wrapper -->
        <div class="relative">
            <!-- Timeline Vertical Line -->
            <div class="absolute left-4 top-2 bottom-2 w-0.5 bg-[#f2dfd5]"></div>

            <div class="space-y-8">
                @foreach($activities as $activity)
                    <div class="flex items-start relative pl-10">
                        <!-- Timeline Dot Point -->
                        <div class="absolute left-[11px] top-1.5 w-3.5 h-3.5 rounded-full border-2 border-white ring-4 ring-[#fff8f6] {{ $activity['dotColor'] }}"></div>
                        
                        <!-- Log Content -->
                        <div class="flex-1 flex flex-col sm:flex-row sm:justify-between sm:items-center">
                            <div>
                                <h4 class="text-sm font-semibold text-[#331200] mb-0.5 font-sans">{{ $activity['title'] }}</h4>
                                <p class="text-sm text-[#897266] font-normal">"{{ $activity['description'] }}"</p>
                            </div>
                            <span class="text-xs text-[#897266] bg-[#fff1eb] px-3 py-1 rounded-full border border-[#f2dfd5] mt-2 sm:mt-0 font-medium">
                                {{ $activity['time'] }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
