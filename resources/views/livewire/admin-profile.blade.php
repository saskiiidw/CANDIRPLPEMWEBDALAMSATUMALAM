<div class="flex gap-8">
    <!-- Left Panel: Profile Detail Overview Card -->
    <div class="w-80 shrink-0">
        <div class="bg-white rounded-3xl border border-[#f2dfd5] p-6 shadow-sm text-center">
            <!-- Large Avatar with Edit Icon -->
            <div class="relative w-32 h-32 mx-auto mb-6">
                <img class="w-32 h-32 rounded-full object-cover border-2 border-[#f2dfd5]" 
                     src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80" 
                     alt="Eleanor Vance">
                <!-- Orange Edit pencil overlay (Image 5) -->
                <button class="absolute bottom-1 right-1 w-8 h-8 rounded-full bg-[#f49b65] border-2 border-white flex items-center justify-center text-[#331200] hover:bg-orange-400 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                </button>
            </div>

            <!-- Profile Identity -->
            <h3 class="text-2xl font-bold text-[#331200] font-headline-md mb-1">{{ $firstName }} {{ $lastName }}</h3>
            <p class="text-sm font-semibold text-[#8e4e14] mb-2">Super Administrator</p>
            <p class="text-xs text-[#897266] mb-6">{{ $email }}</p>

            <hr class="border-[#f2dfd5] mb-6">

            <!-- Metadata info grid -->
            <div class="text-left space-y-4 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-[#897266] font-semibold text-xs uppercase tracking-wider">Status</span>
                    <span class="px-3 py-0.5 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                        Active
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[#897266] font-semibold text-xs uppercase tracking-wider">Last Login</span>
                    <span class="text-xs font-semibold text-[#231914]">Today, 08:42 AM</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Panel: Form & Preferences -->
    <div class="flex-1 space-y-6">
        <!-- Card 1: Personal Information -->
        <div class="bg-white rounded-3xl border border-[#f2dfd5] p-6 shadow-sm">
            <div class="flex items-center space-x-2 text-sm font-semibold text-[#8e4e14] mb-6 uppercase tracking-wider">
                <svg class="w-5 h-5 text-[#8e4e14]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span>Personal Information</span>
            </div>

            @if (session()->has('message'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl text-sm font-semibold flex items-center">
                    <svg class="w-5 h-5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit.prevent="save" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- First Name -->
                    <div class="space-y-1.5">
                        <label for="firstName" class="text-xs font-semibold text-[#897266] uppercase tracking-wider">First Name</label>
                        <input type="text" id="firstName" wire:model="firstName" 
                               class="w-full bg-[#fff8f6] border border-[#f2dfd5] rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#9b4500] focus:border-transparent text-[#231914] transition">
                        @error('firstName') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Last Name -->
                    <div class="space-y-1.5">
                        <label for="lastName" class="text-xs font-semibold text-[#897266] uppercase tracking-wider">Last Name</label>
                        <input type="text" id="lastName" wire:model="lastName" 
                               class="w-full bg-[#fff8f6] border border-[#f2dfd5] rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#9b4500] focus:border-transparent text-[#231914] transition">
                        @error('lastName') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Email Address -->
                <div class="space-y-1.5">
                    <label for="email" class="text-xs font-semibold text-[#897266] uppercase tracking-wider">Email Address</label>
                    <input type="email" id="email" wire:model="email" 
                           class="w-full bg-[#fff8f6] border border-[#f2dfd5] rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#9b4500] focus:border-transparent text-[#231914] transition">
                    @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Save Button -->
                <div class="flex justify-end">
                    <button type="submit" 
                            class="px-6 py-3 rounded-xl bg-[#8e4e14] text-white font-semibold hover:bg-[#9b4500] transition text-sm shadow-sm flex items-center space-x-1.5">
                        <span>Save Changes</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Card 2: System Preferences -->
        <div class="bg-white rounded-3xl border border-[#f2dfd5] p-6 shadow-sm">
            <div class="flex items-center space-x-2 text-sm font-semibold text-[#8e4e14] mb-6 uppercase tracking-wider">
                <svg class="w-5 h-5 text-[#8e4e14]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                </svg>
                <span>System Preferences</span>
            </div>

            @if (session()->has('pref_message'))
                <div class="mb-4 p-3 bg-orange-50 border border-[#f2dfd5] text-[#8e4e14] rounded-xl text-xs font-semibold">
                    {{ session('pref_message') }}
                </div>
            @endif

            <div class="space-y-6 divide-y divide-[#f2dfd5]">
                <!-- Preference item 1: Email Notifications toggle (Image 5 style) -->
                <div class="flex items-center justify-between pt-0">
                    <div>
                        <h4 class="text-sm font-bold text-[#331200]">Email Notifications</h4>
                        <p class="text-xs text-[#897266] mt-0.5">Receive daily summary reports.</p>
                    </div>
                    <!-- Toggle slider element -->
                    <button wire:click="toggleEmailNotifications" 
                            class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $emailNotifications ? 'bg-[#8e4e14]' : 'bg-[#f2dfd5]' }}">
                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $emailNotifications ? 'translate-x-5' : 'translate-x-0' }}"></span>
                    </button>
                </div>

                <!-- Preference item 2: Two-Factor Authentication -->
                <div class="flex items-center justify-between pt-6">
                    <div>
                        <h4 class="text-sm font-bold text-[#331200]">Two-Factor Authentication</h4>
                        <p class="text-xs text-[#897266] mt-0.5">Enhance account security.</p>
                    </div>
                    <button wire:click="enableTwoFactor" 
                            class="text-xs font-bold text-[#8e4e14] hover:underline"
                            {{ $twoFactor ? 'disabled' : '' }}>
                        {{ $twoFactor ? 'Enabled' : 'Enable' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Card 3: Danger Zone -->
        <div class="bg-white rounded-3xl border border-red-200/50 p-6 shadow-sm border-l-4 border-l-red-500">
            <div class="flex items-center space-x-2 text-sm font-semibold text-red-500 mb-2 uppercase tracking-wider">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span>Danger Zone</span>
            </div>
            <p class="text-xs text-[#897266] mb-6">Actions here cannot be undone. Proceed with caution.</p>

            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-bold text-[#331200]">Sign Out of All Sessions</h4>
                    <p class="text-xs text-[#897266] mt-0.5">Force log out from all devices.</p>
                </div>
                <!-- Logout Button matching Image 5 (capsule layout) -->
                <button wire:click="logout"
                        class="flex items-center space-x-1.5 bg-red-50 hover:bg-red-100/50 text-red-500 font-bold px-5 py-2.5 rounded-full transition text-xs shadow-sm border border-red-200/40">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Logout</span>
                </button>
            </div>
        </div>
    </div>
</div>
