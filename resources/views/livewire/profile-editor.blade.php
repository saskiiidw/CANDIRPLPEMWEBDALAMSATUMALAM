<div class="max-w-xl mx-auto bg-white border border-[#feeae0] rounded-3xl p-8 shadow-md space-y-6">
    <!-- Header -->
    <div class="border-b border-[#feeae0] pb-4">
        <h1 class="text-3xl font-extrabold text-[#231914] font-display-lg">Edit Profil</h1>
        <p class="text-sm text-[#897266]">Perbarui detail akun Anda.</p>
    </div>

    <!-- Success Message -->
    <div x-data="{ show: false }" x-on:profile-updated.window="show = true; setTimeout(() => show = false, 3000)" x-show="show" x-transition class="p-4 bg-green-100 border-l-4 border-green-500 text-green-700 text-sm rounded-r-xl">
        Profil berhasil diperbarui!
    </div>

    <form wire:submit="save" class="space-y-6">
        <!-- Photo Upload -->
        <div class="flex flex-col items-center gap-4">
            <!-- Photo Preview -->
            <div class="relative w-24 h-24 rounded-full border-4 border-[#ffab69] overflow-hidden bg-orange-50 shadow-inner">
                @if ($photo)
                    <img src="{{ $photo->temporaryUrl() }}" alt="Preview" class="w-full h-full object-cover">
                @else
                    <img src="{{ auth()->user()->photo_path ? asset('storage/' . auth()->user()->photo_path) : 'https://api.dicebear.com/7.x/adventurer/svg?seed=' . auth()->user()->name }}" 
                         alt="Profile photo" 
                         class="w-full h-full object-cover">
                @endif
            </div>

            <!-- Upload input -->
            <div class="relative">
                <input 
                    type="file" 
                    wire:model="photo" 
                    id="photo-input"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                >
                <label 
                    for="photo-input"
                    class="px-4 py-2 bg-[#FAF3EB] border border-[#f2dfd5] hover:border-[#ffab69] hover:bg-[#ffab69]/10 text-[#9b4500] text-xs font-bold rounded-xl cursor-pointer block transition-all"
                >
                    Ubah Foto
                </label>
            </div>
            @error('photo') <span class="text-xs text-red-600 font-bold">{{ $message }}</span> @enderror
        </div>

        <!-- Name Input -->
        <div class="space-y-2">
            <label for="name" class="block text-sm font-bold text-[#231914] font-headline-md">Nama</label>
            <input 
                type="text" 
                wire:model="name" 
                id="name"
                class="block w-full border border-[#f2dfd5] focus:border-[#ffab69] focus:ring-[#ffab69] bg-[#fff8f6] rounded-2xl text-sm placeholder-[#897266]"
            >
            @error('name') <span class="text-xs text-red-600 font-bold">{{ $message }}</span> @enderror
        </div>

        <!-- Phone Input -->
        <div class="space-y-2">
            <label for="phone" class="block text-sm font-bold text-[#231914] font-headline-md">Nomor Telepon</label>
            <input 
                type="text" 
                wire:model="phone" 
                id="phone"
                placeholder="E.g., +62 812-3456-7890..."
                class="block w-full border border-[#f2dfd5] focus:border-[#ffab69] focus:ring-[#ffab69] bg-[#fff8f6] rounded-2xl text-sm placeholder-[#897266]"
            >
            @error('phone') <span class="text-xs text-red-600 font-bold">{{ $message }}</span> @enderror
        </div>

        <!-- Store Name (Sellers only) -->
        @if (auth()->user()->role === 'penjual')
            <div class="space-y-2">
                <label for="store_name" class="block text-sm font-bold text-[#231914] font-headline-md">Nama Toko</label>
                <input 
                    type="text" 
                    wire:model="store_name" 
                    id="store_name"
                    class="block w-full border border-[#f2dfd5] focus:border-[#ffab69] focus:ring-[#ffab69] bg-[#fff8f6] rounded-2xl text-sm placeholder-[#897266]"
                >
                @error('store_name') <span class="text-xs text-red-600 font-bold">{{ $message }}</span> @enderror
            </div>
        @endif

        <!-- Submit Button -->
        <div class="pt-4">
            <button 
                type="submit"
                class="w-full py-4 bg-[#8e4e14] hover:bg-[#9b4500] text-white font-extrabold rounded-full transition-all shadow-md active:scale-95 flex items-center justify-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
