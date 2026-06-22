<div wire:poll.10000ms>
    @push('header-left')
        <div class="relative w-80">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                <span class="material-symbols-outlined text-lg text-[#8A7160]">search</span>
            </span>
            <input type="text"
                   placeholder="Search system..."
                   class="w-full pl-11 pr-4 py-2.5 bg-white border border-[#FCEEE5] rounded-full text-xs focus:outline-none focus:ring-2 focus:ring-[#E27226]/40 focus:border-transparent text-[#331C0E] placeholder-[#8A7160] transition shadow-sm">
        </div>
    @endpush

    <!-- Page Header (Image 1 style: Stacked Brand Title & Date Pill) -->
    <div class="flex justify-between items-start mb-10">
        <div>
            <h1 class="text-[3.5rem] font-extrabold text-[#7A3205] leading-[0.9] tracking-tight font-display mb-1 uppercase">
                Smart<br>Canteen<br>Admin
            </h1>
        </div>
        <div class="flex items-center gap-2 bg-[#FCEEE5] border border-[#FCEEE5] px-4 py-2 rounded-full text-[#9E460B] text-xs font-bold shadow-sm cursor-pointer hover:bg-[#FDF2EA] transition">
            <span class="material-symbols-outlined text-sm">calendar_today</span>
            <span>Today, {{ now()->format('M d') }}</span>
        </div>
    </div>

    <!-- Overview Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-extrabold text-[#331C0E] font-display">Overview</h2>
        <p class="text-sm text-[#8A7160] mt-1">Here's what's happening across the campus today.</p>
    </div>

    <!-- Primary Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">

        <!-- Orders Today (Accent Card) -->
        <div class="bg-[#8E4E14] rounded-[2rem] p-8 text-white shadow-sm flex flex-col justify-between h-56 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-[#B6662E] rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-2xl">description</span>
                </div>
                <span class="bg-white/10 text-white border border-white/10 text-[10px] font-bold px-3 py-1.5 rounded-full shrink-0">
                    @if($ordersTrend >= 0)
                        ↑ +{{ $ordersTrend }}% from yesterday
                    @else
                        ↓ {{ $ordersTrend }}% from yesterday
                    @endif
                </span>
            </div>
            <div class="mt-4">
                <p class="text-[10px] font-extrabold uppercase tracking-widest text-[#FFF1E5]/80">Orders Today</p>
                <p class="text-5xl font-extrabold mt-2 font-display leading-none">{{ number_format($ordersToday) }}</p>
            </div>
        </div>

        <!-- Total Students -->
        <div class="bg-white border border-[#FCEEE5] rounded-[2rem] p-8 shadow-sm flex flex-col justify-between h-56">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-[#FFF1E5] text-[#E27226] rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">school</span>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-[10px] font-extrabold uppercase tracking-widest text-[#8A7160]">Total Students</p>
                <div class="flex items-baseline gap-2 mt-2">
                    <p class="text-4xl font-extrabold text-[#331C0E] font-display leading-none">{{ number_format($totalStudents) }}</p>
                    <span class="text-xs font-bold text-[#E27226]">↑ 2.4%</span>
                </div>
            </div>
        </div>

        <!-- Completed Today -->
        <div class="bg-white border border-[#FCEEE5] rounded-[2rem] p-8 shadow-sm flex flex-col justify-between h-56">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 bg-[#FDF2EA] text-[#E27226] rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">check_circle</span>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-[10px] font-extrabold uppercase tracking-widest text-[#8A7160]">Completed (Today)</p>
                <p class="text-4xl font-extrabold mt-2 text-[#331C0E] font-display leading-none">{{ number_format($completedToday) }}</p>
                <div class="w-full bg-[#FAF3EB] rounded-full h-1.5 overflow-hidden mt-3">
                    <div class="bg-[#9E460B] h-full rounded-full transition-all" style="width: {{ $completionRate }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">

        <!-- Revenue Today -->
        <div class="bg-white border border-[#FCEEE5] rounded-[2rem] p-6 shadow-sm flex items-center gap-4 lg:col-span-1">
            <div class="w-12 h-12 bg-[#FFF1E5] text-[#9E460B] rounded-full flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-2xl">payments</span>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] font-extrabold uppercase tracking-widest text-[#8A7160]">Revenue Today</p>
                <p class="text-lg font-extrabold text-[#331C0E] font-display mt-0.5 truncate">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Total Sellers -->
        <div class="bg-white border border-[#FCEEE5] rounded-[2rem] p-6 shadow-sm flex items-center gap-4 lg:col-span-1">
            <div class="w-12 h-12 bg-[#FFF1E5] text-[#9E460B] rounded-full flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-2xl">storefront</span>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] font-extrabold uppercase tracking-widest text-[#8A7160]">Total Sellers</p>
                <p class="text-2xl font-extrabold text-[#331C0E] font-display mt-0.5">{{ $totalSellers }}</p>
            </div>
        </div>

        <!-- Active Now -->
        <div class="bg-white border border-[#FCEEE5] rounded-[2rem] p-6 shadow-sm flex items-center gap-4 lg:col-span-1">
            <div class="w-12 h-12 bg-[#FFF1E5] text-[#9E460B] rounded-full flex items-center justify-center relative shrink-0">
                <span class="material-symbols-outlined text-2xl">bolt</span>
                <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-green-500 rounded-full border border-white"></span>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] font-extrabold uppercase tracking-widest text-[#8A7160]">Active Now</p>
                <p class="text-2xl font-extrabold text-[#331C0E] font-display mt-0.5">{{ $activeSellers }}</p>
            </div>
        </div>

        <!-- Rejected/Cancelled Today (Double Width, image 1 style) -->
        <div class="bg-white border border-red-100 rounded-[2rem] p-6 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 lg:col-span-2">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-red-50 text-red-500 rounded-full flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl">cancel</span>
                </div>
                <div>
                    <p class="text-[10px] font-extrabold uppercase tracking-widest text-[#8A7160]">Rejected/Cancelled Today</p>
                    <p class="text-2xl font-extrabold text-[#331C0E] font-display mt-0.5">{{ $rejectedToday }}</p>
                </div>
            </div>
            <div class="sm:text-right">
                @if($rejectedToday > 0)
                    <p class="text-xs text-red-600 font-semibold max-w-[200px]">Requires attention. Mainly due to item unavailability.</p>
                @else
                    <p class="text-xs text-green-600 font-semibold max-w-[200px]">All clear today. No orders rejected or cancelled.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Pending Verification Alert -->
    @if($pendingVerification > 0)
        <div class="bg-[#FFF1E5] border border-[#FCEEE5] rounded-[2rem] p-6 mb-8 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-4">
                <span class="material-symbols-outlined text-[#E27226] text-3xl">pending_actions</span>
                <div>
                    <p class="text-sm font-extrabold text-[#331C0E]">{{ $pendingVerification }} Seller(s) Awaiting Verification</p>
                    <p class="text-xs text-[#8A7160]">Review and approve seller registrations to let them go live.</p>
                </div>
            </div>
            <a href="{{ route('admin.seller-verification') }}" class="bg-[#9E460B] hover:bg-[#803708] text-white text-xs font-bold px-5 py-3 rounded-full transition shrink-0 shadow-sm active:scale-95">
                Review Now
            </a>
        </div>
    @endif

    <!-- Recent Activity -->
    <div class="bg-white rounded-[2rem] border border-[#FCEEE5] p-8 shadow-sm">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h3 class="text-lg font-extrabold text-[#331C0E] font-display">Recent Activity</h3>
                <p class="text-xs text-[#8A7160] mt-1">Last 8 system events from the audit log.</p>
            </div>
            <a href="{{ route('admin.audit-log') }}" class="text-xs font-bold text-[#9E460B] hover:underline flex items-center gap-1">
                <span>View Full Log</span>
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>

        @if(count($activities) === 0)
            <div class="text-center py-12 text-[#8A7160]">
                <span class="material-symbols-outlined text-4xl text-gray-300">history</span>
                <p class="text-sm font-bold mt-2">No activity recorded yet.</p>
            </div>
        @else
            <div class="relative pl-8 space-y-6 before:absolute before:left-3 before:top-2 before:bottom-2 before:w-0.5 before:bg-[#FCEEE5]">
                @foreach($activities as $activity)
                    <div class="relative">
                        <div class="absolute -left-[27px] top-1.5 w-3.5 h-3.5 rounded-full border-2 border-white ring-2 ring-[#FFFBF7] {{ $activity['dotColor'] }}"></div>
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h4 class="text-xs font-extrabold text-[#331C0E]">{{ $activity['title'] }}</h4>
                                    <span class="text-[10px] text-[#8A7160] font-semibold">by {{ $activity['user'] }}</span>
                                </div>
                                <p class="text-xs text-[#8A7160] mt-1 line-clamp-1">{{ $activity['description'] }}</p>
                            </div>
                            <span class="text-[10px] font-bold text-[#8A7160] bg-[#FFF1E5] border border-[#FCEEE5] px-3.5 py-1.5 rounded-full shrink-0">
                                {{ $activity['time'] }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div></div>
