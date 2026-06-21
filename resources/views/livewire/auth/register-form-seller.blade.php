<div class="min-h-screen flex flex-col font-body-md overflow-x-hidden relative">
    <div class="absolute top-[-100px] right-[-100px] w-[600px] h-[600px] rounded-full -z-10"
         style="background: radial-gradient(circle, rgba(255,171,105,0.15) 0%, rgba(255,248,246,0) 70%);"></div>

    <header class="w-full flex justify-between items-center px-gutter py-4 max-w-container-max mx-auto">
        <span class="font-display-lg text-headline-md text-primary">CampusCrave</span>
        <a href="{{ route('login') }}" wire:navigate class="font-label-lg text-label-lg text-primary hover:text-primary-container transition-colors">Masuk</a>
    </header>

    <main class="flex-grow pt-8 pb-24 px-gutter max-w-container-max mx-auto w-full relative z-10">
        <div class="max-w-2xl mx-auto">
            <div class="text-center mb-12">
                <h1 class="font-display-lg text-display-lg text-primary mb-4">Bergabung jadi Penjual</h1>
                <p class="font-body-lg text-body-lg text-on-surface-variant">Daftarkan kantin Anda dan mulai jualan di CampusCrave.</p>
            </div>

            <div class="mb-8 rounded-lg p-6 flex items-center gap-4 border-l-4 border-l-secondary-container glass-card">
                <div class="bg-secondary-fixed w-12 h-12 rounded-full flex items-center justify-center text-on-secondary-fixed">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">pending_actions</span>
                </div>
                <div>
                    <h3 class="font-headline-md text-headline-md text-on-surface mb-1">Status: Menunggu Verifikasi</h3>
                    <p class="font-body-md text-body-md text-on-surface-variant">Setelah daftar, akun akan diverifikasi admin sebelum bisa mulai berjualan.</p>
                </div>
            </div>

            <form wire:submit="register" class="glass-card rounded-xl p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="name" class="font-label-lg text-label-lg text-on-surface block">Nama Penjual</label>
                        <input wire:model.blur="name" id="name" type="text" placeholder="John Doe"
                               class="w-full bg-surface-container-low border-2 border-outline-variant rounded-DEFAULT px-4 py-3 font-body-md text-body-md focus:border-primary focus:ring-0 transition-colors @error('name') border-error @enderror">
                        @error('name') <p class="text-error text-label-md">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="store_name" class="font-label-lg text-label-lg text-on-surface block">Nama Kantin</label>
                        <input wire:model.live.debounce.500ms="store_name" id="store_name" type="text" placeholder="Kantin Mas Eddy"
                               class="w-full bg-surface-container-low border-2 border-outline-variant rounded-DEFAULT px-4 py-3 font-body-md text-body-md focus:border-primary focus:ring-0 transition-colors @error('store_name') border-error @enderror">
                        @error('store_name') <p class="text-error text-label-md">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="email" class="font-label-lg text-label-lg text-on-surface block">Email</label>
                    <input wire:model.live.debounce.500ms="email" id="email" type="email" placeholder="hello@campuskitchen.com"
                           class="w-full bg-surface-container-low border-2 border-outline-variant rounded-DEFAULT px-4 py-3 font-body-md text-body-md focus:border-primary focus:ring-0 transition-colors @error('email') border-error @enderror">
                    @error('email') <p class="text-error text-label-md">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="password" class="font-label-lg text-label-lg text-on-surface block">Password</label>
                    <input wire:model.blur="password" id="password" type="password" placeholder="••••••••"
                           class="w-full bg-surface-container-low border-2 border-outline-variant rounded-DEFAULT px-4 py-3 font-body-md text-body-md focus:border-primary focus:ring-0 transition-colors @error('password') border-error @enderror">
                    @error('password') <p class="text-error text-label-md">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="password_confirmation" class="font-label-lg text-label-lg text-on-surface block">Konfirmasi Password</label>
                    <input wire:model.blur="password_confirmation" id="password_confirmation" type="password" placeholder="••••••••"
                           class="w-full bg-surface-container-low border-2 border-outline-variant rounded-DEFAULT px-4 py-3 font-body-md text-body-md focus:border-primary focus:ring-0 transition-colors">
                </div>

                <div class="pt-6">
                    <button type="submit" wire:loading.attr="disabled" wire:target="register"
                            class="w-full bg-primary text-on-primary font-headline-md text-headline-md py-4 rounded-full hover:bg-primary-container hover:shadow-xl transition-all flex items-center justify-center gap-2 disabled:opacity-60">
                        <span wire:loading.remove wire:target="register">Daftar Sebagai Penjual</span>
                        <span wire:loading wire:target="register">Memproses...</span>
                        <span wire:loading.remove wire:target="register" class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">arrow_forward</span>
                    </button>
                </div>
            </form>

            <p class="text-center mt-6 font-body-md text-body-md text-on-surface-variant">
                Daftar sebagai mahasiswa?
                <a href="{{ route('register', ['role' => 'mahasiswa']) }}" wire:navigate class="text-primary font-semibold hover:underline">Klik di sini</a>
            </p>
        </div>
    </main>
</div>