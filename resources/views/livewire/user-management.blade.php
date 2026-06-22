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
                   placeholder="Cari pengguna..." 
                   class="w-full pl-10 pr-4 py-2 bg-white border border-[#f2dfd5] rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-[#9b4500] focus:border-transparent text-[#231914] placeholder-[#897266] transition">
        </div>
    @endpush

    <!-- Left Panel: User Listing Grid -->
    <div class="flex-1">
        <div class="mb-6">
            <h1 class="text-3xl font-extrabold text-[#331200] font-headline-md mb-1">Manajemen Pengguna</h1>
            <p class="text-sm text-[#897266] font-body-md">Kelola akun mahasiswa dan penjual kantin.</p>
        </div>

        @if (session()->has('message'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl text-sm font-semibold flex items-center">
                <svg class="w-5 h-5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('message') }}
            </div>
        @endif

        <!-- Filter bar (Tab buttons capsule) -->
        <div class="flex items-center space-x-4 mb-6">
            <div class="inline-flex bg-[#fff1eb] border border-[#f2dfd5] p-1 rounded-full">
                <!-- Student Button -->
                <button wire:click="setTab('student')" 
                        class="px-6 py-2 rounded-full text-sm font-semibold transition duration-150 {{ $tab === 'student' ? 'bg-[#f49b65] text-[#331200] shadow-sm' : 'text-[#897266] hover:text-[#9b4500]' }}">
                    Mahasiswa
                </button>
                <!-- Seller Button -->
                <button wire:click="setTab('seller')" 
                        class="px-6 py-2 rounded-full text-sm font-semibold transition duration-150 {{ $tab === 'seller' ? 'bg-[#f49b65] text-[#331200] shadow-sm' : 'text-[#897266] hover:text-[#9b4500]' }}">
                    Penjual
                </button>
            </div>

            <!-- Filter Icon button -->
            <button class="p-2.5 bg-white border border-[#f2dfd5] rounded-full hover:bg-[#fff1eb] transition text-[#897266] hover:text-[#9b4500]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 8.293A1 1 0 013 7.586V4z"></path>
                </svg>
            </button>
        </div>

        <!-- Grid of User Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($users as $user)
                <div wire:click="selectUser({{ $user->id }})"
                     class="cursor-pointer bg-white rounded-3xl p-5 border transition duration-150 flex flex-col justify-between hover:border-[#f49b65] hover:shadow-sm {{ $selectedUserId === $user->id ? 'border-[#8e4e14] bg-[#fff8f6]' : 'border-[#f2dfd5]' }}">
                    <div>
                        <!-- Header containing Avatar & Status -->
                        <div class="flex justify-between items-start mb-4">
                            <!-- User Avatar -->
                            @if($user->photo_path)
                                <img class="w-12 h-12 rounded-full object-cover border border-[#f2dfd5]" src="{{ $user->photo_path }}" alt="Avatar">
                            @else
                                <div class="w-12 h-12 rounded-full bg-[#f2dfd5] flex items-center justify-center font-bold text-[#8e4e14] text-sm uppercase">
                                    {{ substr($user->name, 0, 2) }}
                                </div>
                            @endif

                            <!-- Status Badge -->
                            @if(!$user->is_active)
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-500 border border-red-100 flex items-center">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                                    Tidak Aktif
                                </span>
                            @elseif($user->role === 'mahasiswa' && is_null($user->email_verified_at))
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#fff1eb] text-[#8e4e14] border border-[#f2dfd5] flex items-center">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#f49b65] mr-1.5"></span>
                                    Menunggu
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200 flex items-center">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                    Aktif
                                </span>
                            @endif
                        </div>

                        <!-- Name and Secondary fields -->
                        <h4 class="text-lg font-bold text-[#331200] font-headline-md leading-tight mb-1">{{ $user->name }}</h4>
                        
                        <p class="text-xs text-[#897266] mb-3">
                            @if($user->role === 'mahasiswa')
                                {{ $user->student_id ?? 'S1029384' }} • {{ $user->faculty ?? 'Teknik' }}
                            @else
                                {{ $user->store_name }}
                            @endif
                        </p>
                    </div>

                    <!-- Email Detail block -->
                    <div class="flex items-center space-x-2 text-xs text-[#897266] pt-3 border-t border-[#f2dfd5]">
                        <svg class="w-4 h-4 shrink-0 text-[#897266]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="break-all">{{ $user->email }}</span>
                    </div>
                </div>
            @empty
                <div class="col-span-2 py-10 bg-white rounded-3xl border border-[#f2dfd5] text-center text-[#897266]">
                    Tidak ada pengguna yang ditemukan.
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Right Panel: User Detail Card -->
    <div class="w-96 shrink-0">
        @if($selectedUser)
            <div class="bg-white rounded-3xl border border-[#f2dfd5] p-6 shadow-sm flex flex-col h-full justify-between min-h-[500px]">
                <div class="text-center">
                    <!-- Photo Header -->
                    <div class="flex justify-center mb-6">
                        @if($selectedUser->photo_path)
                            <img class="w-24 h-24 rounded-full object-cover border-2 border-[#f2dfd5]" src="{{ $selectedUser->photo_path }}" alt="Avatar">
                        @else
                            <div class="w-24 h-24 rounded-full bg-[#f2dfd5] flex items-center justify-center font-bold text-[#8e4e14] text-3xl uppercase border border-[#f2dfd5]">
                                {{ substr($selectedUser->name, 0, 2) }}
                            </div>
                        @endif
                    </div>

                    <!-- User Name & Role Header -->
                    <h3 class="text-2xl font-bold text-[#331200] mb-1 font-headline-md">{{ $selectedUser->name }}</h3>
                    <p class="text-sm font-semibold text-[#8e4e14] mb-8 font-sans">
                        {{ $selectedUser->role === 'mahasiswa' ? 'Akun Mahasiswa' : 'Akun Penjual' }}
                    </p>

                    <!-- User Specific Metadata Fields list (Image 3 style) -->
                    <div class="text-left space-y-4 border-t border-[#f2dfd5] pt-6 mb-6">
                        <!-- student ID / Store Name -->
                        <div>
                            <span class="text-xs uppercase tracking-wider font-semibold text-[#897266]">
                                {{ $selectedUser->role === 'mahasiswa' ? 'NIM' : 'Nama Toko' }}
                            </span>
                            <p class="text-sm font-semibold text-[#231914] mt-0.5">
                                {{ $selectedUser->role === 'mahasiswa' ? ($selectedUser->student_id ?? 'S1029384') : $selectedUser->store_name }}
                            </p>
                        </div>

                        <!-- Email -->
                        <div>
                            <span class="text-xs uppercase tracking-wider font-semibold text-[#897266]">Email</span>
                            <p class="text-sm font-semibold text-[#231914] mt-0.5 break-all">
                                {{ $selectedUser->email }}
                            </p>
                        </div>

                        <!-- Faculty / Store Phone -->
                        <div>
                            <span class="text-xs uppercase tracking-wider font-semibold text-[#897266]">
                                {{ $selectedUser->role === 'mahasiswa' ? 'Fakultas' : 'Telepon' }}
                            </span>
                            <p class="text-sm font-semibold text-[#231914] mt-0.5">
                                {{ $selectedUser->role === 'mahasiswa' ? ($selectedUser->faculty ?? 'Teknik') : ($selectedUser->phone ?? '-') }}
                            </p>
                        </div>

                        <!-- Joined Date -->
                        <div>
                            <span class="text-xs uppercase tracking-wider font-semibold text-[#897266]">Tanggal Bergabung</span>
                            <p class="text-sm font-semibold text-[#231914] mt-0.5">
                                {{ $selectedUser->created_at->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Button Block -->
                <div class="flex flex-col space-y-3 pt-6 border-t border-[#f2dfd5] mt-auto">
                    <!-- View User Detail Button -->
                    <a href="#" class="py-3 px-4 rounded-xl bg-[#8e4e14] text-white font-semibold hover:bg-[#9b4500] transition text-sm flex items-center justify-center space-x-1.5 shadow-sm text-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <span>Lihat Detail Pengguna</span>
                    </a>

                    <div class="grid grid-cols-2 gap-3">
                        <!-- Activate Button -->
                        <button wire:click="activate({{ $selectedUser->id }})" 
                                class="py-3 px-4 rounded-xl bg-[#fff1eb] text-[#8e4e14] border border-[#f2dfd5] font-semibold hover:bg-[#feeae0] transition text-sm {{ $selectedUser->is_active ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ $selectedUser->is_active ? 'disabled' : '' }}>
                            Aktifkan
                        </button>
                        <!-- Deactivate Button -->
                        <button wire:click="deactivate({{ $selectedUser->id }})" 
                                class="py-3 px-4 rounded-xl bg-red-50 text-red-500 font-semibold hover:bg-red-100/50 transition text-sm {{ !$selectedUser->is_active ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ !$selectedUser->is_active ? 'disabled' : '' }}>
                            Nonaktifkan
                        </button>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-3xl border border-[#f2dfd5] p-6 shadow-sm text-center py-20 text-[#897266]">
                Pilih pengguna untuk melihat detail mereka.
            </div>
        @endif
    </div>
</div>
