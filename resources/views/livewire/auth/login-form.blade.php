<div class="bg-surface-container-lowest rounded-lg shadow-[0_20px_40px_-10px_rgba(142,78,20,0.1)] w-full max-w-md overflow-hidden relative">
    <div class="bg-surface-container-low p-card-padding text-center relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-primary-fixed rounded-full mix-blend-multiply filter blur-xl opacity-70"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-secondary-fixed rounded-full mix-blend-multiply filter blur-xl opacity-70"></div>

        <h1 class="font-display-lg text-display-lg text-primary relative z-10 mb-unit">SmartCanteen</h1>
        <p class="font-body-md text-body-md text-on-surface-variant relative z-10">Masuk ke kantin pintar Anda.</p>
    </div>

    <div class="p-card-padding">
        @if ($errorMessage)
            <div class="mb-gutter px-4 py-3 rounded-[12px] bg-error-container text-on-error-container font-body-md text-sm">
                {{ $errorMessage }}
            </div>
        @endif

        <form wire:submit="login" class="space-y-gutter">
            <div class="space-y-unit">
                <label class="block font-label-lg text-label-lg text-on-surface" for="email">Alamat Email</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant" style="font-variation-settings: 'FILL' 0;">mail</span>
                    <input wire:model="email" id="email" type="email" required autofocus autocomplete="username" class="w-full pl-12 pr-4 py-3 bg-surface rounded-[16px] border-2 border-surface-variant focus:border-primary focus:ring-0 transition-colors font-body-md text-body-md text-on-surface placeholder:text-on-surface-variant/50" placeholder="student@campus.edu" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="space-y-unit" x-data="{ showPassword: false }">
                <div class="flex justify-between items-center">
                    <label class="block font-label-lg text-label-lg text-on-surface" for="password">Kata Sandi</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" wire:navigate class="font-label-md text-label-md text-primary hover:text-secondary transition-colors">Lupa?</a>
                    @endif
                </div>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant" style="font-variation-settings: 'FILL' 0;">lock</span>
                    <input wire:model="password" id="password" :type="showPassword ? 'text' : 'password'" required autocomplete="current-password" class="w-full pl-12 pr-12 py-3 bg-surface rounded-[16px] border-2 border-surface-variant focus:border-primary focus:ring-0 transition-colors font-body-md text-body-md text-on-surface placeholder:text-on-surface-variant/50" placeholder="••••••••" />

                    <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors focus:outline-none">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;" x-text="showPassword ? 'visibility' : 'visibility_off'">visibility_off</span>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="pt-unit space-y-unit">
                <button type="submit" class="w-full bg-primary text-on-primary py-4 rounded-full font-label-lg text-label-lg hover:shadow-[0_8px_16px_-4px_rgba(155,69,0,0.3)] hover:-translate-y-0.5 transition-all duration-200">
                    Masuk
                </button>

                @if (Route::has('register.choice'))
                    <p class="text-center font-body-md text-body-md text-on-surface-variant mt-gutter">
                        Belum punya akun? 
                        <a href="{{ route('register.choice') }}" wire:navigate class="...">Daftar di sini</a>
                    </p>
                @endif
            </div>
        </form>
    </div>
</div>