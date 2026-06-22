<div class="min-h-screen flex flex-col md:flex-row w-full">
    <!-- Kiri: Brand image -->
    <div class="hidden md:flex md:w-1/2 lg:w-3/5 bg-surface-container relative overflow-hidden">
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-secondary-container/30 rounded-full blur-[80px]"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[60%] h-[60%] bg-tertiary-container/20 rounded-full blur-[100px]"></div>
        <div class="absolute inset-0 bg-cover bg-center z-10"
             style="background-image: url('{{ asset('images/canteen-meal.jpg') }}')"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent z-10"></div>
    </div>

    <!-- Kanan: Form -->
    <div class="w-full md:w-1/2 lg:w-2/5 flex items-center justify-center p-gutter md:p-margin-desktop bg-surface relative">
        <div class="md:hidden absolute top-0 right-0 w-[200px] h-[200px] bg-secondary-container/20 rounded-full blur-[50px] -translate-y-1/2 translate-x-1/4"></div>

        <div class="w-full max-w-md relative z-10">
            <div class="mb-12">
                <h1 class="font-display-lg text-display-lg text-primary tracking-tight">SmartCanteen</h1>
                <p class="font-body-lg text-body-lg text-on-surface-variant mt-2">Daftar sebagai mahasiswa untuk mulai memesan.</p>
            </div>

            <form wire:submit="register" class="space-y-6">
                <!-- Nama -->
                <div>
                    <label for="name" class="block font-label-md text-label-md text-on-surface-variant mb-2 ml-1">Nama Lengkap</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">person</span>
                        <input wire:model.blur="name" id="name" type="text" placeholder="Jane Doe"
                               class="w-full pl-12 pr-4 py-4 bg-surface-container-lowest border-2 border-outline-variant rounded font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all duration-200 placeholder:text-outline/60 @error('name') border-error @enderror">
                    </div>
                    @error('name') <p class="text-error text-label-md mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block font-label-md text-label-md text-on-surface-variant mb-2 ml-1">Email Akademik</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">mail</span>
                        <input wire:model.live.debounce.500ms="email" id="email" type="email" placeholder="jane@unsoed.ac.id"
                               class="w-full pl-12 pr-4 py-4 bg-surface-container-lowest border-2 border-outline-variant rounded font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all duration-200 placeholder:text-outline/60 @error('email') border-error @enderror">
                    </div>
                    @error('email') <p class="text-error text-label-md mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block font-label-md text-label-md text-on-surface-variant mb-2 ml-1">Kata Sandi</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">lock</span>
                        <input wire:model.blur="password" id="password" type="password" placeholder="••••••••"
                               class="w-full pl-12 pr-4 py-4 bg-surface-container-lowest border-2 border-outline-variant rounded font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all duration-200 placeholder:text-outline/60 @error('password') border-error @enderror">
                    </div>
                    @error('password') <p class="text-error text-label-md mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block font-label-md text-label-md text-on-surface-variant mb-2 ml-1">Konfirmasi Kata Sandi</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">lock_reset</span>
                        <input wire:model.blur="password_confirmation" id="password_confirmation" type="password" placeholder="••••••••"
                               class="w-full pl-12 pr-4 py-4 bg-surface-container-lowest border-2 border-outline-variant rounded font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all duration-200 placeholder:text-outline/60">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" wire:loading.attr="disabled" wire:target="register"
                            class="w-full py-4 bg-primary text-on-primary rounded-full font-label-lg text-label-lg flex items-center justify-center gap-2 shadow-sm hover:shadow-md hover:bg-surface-tint hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-primary-container disabled:opacity-60">
                        <span wire:loading.remove wire:target="register">Daftar</span>
                        <span wire:loading wire:target="register">Memproses...</span>
                        <span wire:loading.remove wire:target="register" class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">arrow_forward</span>
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center">
                <p class="font-body-md text-body-md text-on-surface-variant">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline hover:text-tertiary transition-colors" wire:navigate>Masuk</a>
                </p>
                <p class="font-body-md text-body-md text-on-surface-variant mt-2">
                    Daftar sebagai penjual?
                    <a href="{{ route('register', ['role' => 'penjual']) }}" class="text-primary font-semibold hover:underline" wire:navigate>Klik di sini</a>
                </p>
            </div>
        </div>
    </div>
</div>
