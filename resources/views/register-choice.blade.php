{{-- Catatan: <head>/<body>/wrapper layout TETAP pakai yang sudah ada di file kamu sekarang
     (yang sebelumnya sukses render). Ganti hanya bagian <main> ke bawah ini. --}}

<div class="fixed inset-0 -z-10" style="background-image: radial-gradient(circle at 15% 50%, rgba(255, 171, 105, 0.08) 0%, transparent 50%), radial-gradient(circle at 85% 30%, rgba(255, 182, 141, 0.08) 0%, transparent 50%);"></div>

<main class="flex-grow flex items-center justify-center p-gutter relative z-10 w-full max-w-container-max mx-auto my-12 md:my-0"
      x-data="{ role: null }">
    <div class="w-full max-w-3xl bg-[#FFFDF7] rounded-xl p-8 md:p-12 shadow-[0_20px_40px_-10px_rgba(142,78,20,0.08)] border border-surface-variant/50 flex flex-col items-center">

        <div class="text-center mb-10 w-full">
            <span class="material-symbols-outlined text-primary text-5xl mb-4" style="font-variation-settings: 'FILL' 1;">restaurant</span>
            <h1 class="font-display-lg text-display-lg text-on-surface mb-2 tracking-tight">Pilih Peran Anda</h1>
            <p class="font-body-lg text-body-lg text-on-surface-variant max-w-md mx-auto">Silakan pilih peran untuk melanjutkan pendaftaran di CampusCrave.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full mb-10">
            <!-- Mahasiswa -->
            <button type="button" @click="role = 'mahasiswa'"
                    :class="role === 'mahasiswa' ? 'role-card selected' : 'role-card'"
                    class="relative text-left bg-surface rounded-[24px] p-6 shadow-sm flex flex-col items-start focus:outline-none focus:ring-4 focus:ring-primary-container/50 group">
                <div class="absolute top-6 right-6 transition-all duration-300"
                     :class="role === 'mahasiswa' ? 'opacity-100 scale-100' : 'opacity-0 scale-75'">
                    <div class="bg-primary rounded-full w-8 h-8 flex items-center justify-center text-white shadow-md">
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">check</span>
                    </div>
                </div>
                <div class="w-16 h-16 rounded-full flex items-center justify-center text-primary mb-6 transition-colors duration-300"
                     :class="role === 'mahasiswa' ? 'bg-primary-container text-white' : 'bg-surface-container'">
                    <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">local_pizza</span>
                </div>
                <h2 class="font-headline-md text-headline-md text-on-surface mb-2">Mahasiswa</h2>
                <p class="font-body-md text-body-md text-on-surface-variant">Pesan makanan favoritmu dengan mudah dari kantin kampus.</p>
            </button>

            <!-- Penjual -->
            <button type="button" @click="role = 'penjual'"
                    :class="role === 'penjual' ? 'role-card selected' : 'role-card'"
                    class="relative text-left bg-surface rounded-[24px] p-6 shadow-sm flex flex-col items-start focus:outline-none focus:ring-4 focus:ring-primary-container/50 group">
                <div class="absolute top-6 right-6 transition-all duration-300"
                     :class="role === 'penjual' ? 'opacity-100 scale-100' : 'opacity-0 scale-75'">
                    <div class="bg-primary rounded-full w-8 h-8 flex items-center justify-center text-white shadow-md">
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">check</span>
                    </div>
                </div>
                <div class="w-16 h-16 rounded-full flex items-center justify-center text-primary mb-6 transition-colors duration-300"
                     :class="role === 'penjual' ? 'bg-primary-container text-white' : 'bg-surface-container'">
                    <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">storefront</span>
                </div>
                <h2 class="font-headline-md text-headline-md text-on-surface mb-2">Penjual</h2>
                <p class="font-body-md text-body-md text-on-surface-variant">Mulai berjualan dan layani komunitas kampus dengan hidangan terbaikmu.</p>
            </button>
        </div>

        <div class="w-full flex justify-center">
            <a :href="role ? '/register/' + role : '#'"
               :class="role
                    ? 'bg-primary text-on-primary hover:shadow-[0_8px_16px_-4px_rgba(155,69,0,0.3)] hover:-translate-y-0.5 cursor-pointer'
                    : 'bg-surface-variant text-on-surface-variant opacity-50 cursor-not-allowed pointer-events-none'"
               class="font-label-lg text-label-lg py-4 px-12 rounded-full transition-all duration-300 shadow-sm w-full md:w-auto text-center"
               wire:navigate>
                Lanjutkan Pendaftaran
            </a>
        </div>

        <p class="mt-6 font-body-md text-body-md text-on-surface-variant">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline" wire:navigate>Masuk</a>
        </p>
    </div>
</main>