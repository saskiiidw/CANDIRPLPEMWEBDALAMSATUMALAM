<x-guest-layout>
    <style>
        body {
            background-color: #F5EEDC;
        }
        
        .bg-pattern {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(255, 171, 105, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 85% 30%, rgba(255, 182, 141, 0.08) 0%, transparent 50%);
        }

        .role-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid transparent;
        }
        
        .role-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -10px rgba(142, 78, 20, 0.12);
        }

        .role-card.selected {
            border-color: #ffab69;
            background-color: #fff8f6;
            box-shadow: 0 20px 40px -10px rgba(155, 69, 0, 0.15);
        }

        /* Perubahan visual otomatis ketika mendapat class 'selected' */
        .role-card.selected .check-icon {
            opacity: 1 !important;
            transform: scale(1) !important;
        }

        .role-card.selected .icon-wrapper {
            background-color: #ff8c42 !important;
            color: #ffffff !important;
        }
    </style>

    <div class="bg-pattern"></div>

    <main class="flex-grow flex items-center justify-center p-gutter relative z-10 w-full max-w-container-max mx-auto my-12 md:my-0">
          
        <div class="w-full max-w-3xl bg-[#FFFDF7] rounded-xl p-8 md:p-12 shadow-[0_20px_40px_-10px_rgba(142,78,20,0.08)] border border-surface-variant/50 flex flex-col items-center">
            
            <div class="text-center mb-10 w-full">
                <span class="material-symbols-outlined text-primary text-5xl mb-4" style="font-variation-settings: 'FILL' 1;">restaurant</span>
                <h1 class="font-display-lg text-display-lg text-on-surface mb-2 tracking-tight">Pilih Peran Anda</h1>
                <p class="font-body-lg text-body-lg text-on-surface-variant max-w-md mx-auto">Silakan pilih peran untuk melanjutkan pendaftaran di CampusCrave.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full mb-10">
                
                <button type="button" onclick="selectRole(this, 'mahasiswa')"
                        class="role-card relative text-left bg-surface rounded-[24px] p-6 shadow-sm flex flex-col items-start focus:outline-none focus:ring-4 focus:ring-primary-container/50 group">
                    
                    <div class="check-icon absolute top-6 right-6 transition-all duration-300 opacity-0 scale-75">
                        <div class="bg-primary rounded-full w-8 h-8 flex items-center justify-center text-white shadow-md">
                            <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">check</span>
                        </div>
                    </div>

                    <div class="icon-wrapper w-16 h-16 rounded-full flex items-center justify-center mb-6 transition-colors duration-300 bg-surface-container text-primary">
                        <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">local_pizza</span>
                    </div>

                    <h2 class="font-headline-md text-headline-md text-on-surface mb-2">Mahasiswa</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant">Pesan makanan favoritmu dengan mudah dari kantin kampus.</p>
                </button>

                <button type="button" onclick="selectRole(this, 'penjual')"
                        class="role-card relative text-left bg-surface rounded-[24px] p-6 shadow-sm flex flex-col items-start focus:outline-none focus:ring-4 focus:ring-primary-container/50 group">
                    
                    <div class="check-icon absolute top-6 right-6 transition-all duration-300 opacity-0 scale-75">
                        <div class="bg-primary rounded-full w-8 h-8 flex items-center justify-center text-white shadow-md">
                            <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">check</span>
                        </div>
                    </div>

                    <div class="icon-wrapper w-16 h-16 rounded-full flex items-center justify-center mb-6 transition-colors duration-300 bg-surface-container text-primary">
                        <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">storefront</span>
                    </div>

                    <h2 class="font-headline-md text-headline-md text-on-surface mb-2">Penjual</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant">Mulai berjualan dan layani komunitas kampus dengan hidangan terbaikmu.</p>
                </button>

            </div>

            <div class="w-full flex justify-center mt-4">
                
                <button id="btn-disabled" type="button" 
                        class="bg-surface-variant text-on-surface-variant opacity-50 cursor-not-allowed font-label-lg text-label-lg py-4 px-12 rounded-full shadow-sm w-full md:w-auto text-center block transition-all duration-300">
                    Lanjutkan Pendaftaran
                </button>

                <a id="btn-mahasiswa" href="/register/mahasiswa" wire:navigate style="display: none;"
                   class="bg-primary text-on-primary hover:shadow-[0_8px_16px_-4px_rgba(155,69,0,0.3)] hover:-translate-y-0.5 cursor-pointer font-label-lg text-label-lg py-4 px-12 rounded-full transition-all duration-300 shadow-sm w-full md:w-auto text-center">
                    Lanjutkan Pendaftaran
                </a>

                <a id="btn-penjual" href="/register/penjual" wire:navigate style="display: none;"
                   class="bg-primary text-on-primary hover:shadow-[0_8px_16px_-4px_rgba(155,69,0,0.3)] hover:-translate-y-0.5 cursor-pointer font-label-lg text-label-lg py-4 px-12 rounded-full transition-all duration-300 shadow-sm w-full md:w-auto text-center">
                    Lanjutkan Pendaftaran
                </a>

            </div>

        </div>
    </main>

    <script>
        function selectRole(element, role) {
            // Hapus class 'selected' dari semua kartu
            document.querySelectorAll('.role-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Tambahkan class 'selected' pada kartu yang dipilih
            element.classList.add('selected');

            // Atur tampilan tombol berdasarkan role yang dipilih
            document.getElementById('btn-disabled').style.display = 'none';
            document.getElementById('btn-mahasiswa').style.display = role === 'mahasiswa' ? 'block' : 'none';
            document.getElementById('btn-penjual').style.display = role === 'penjual' ? 'block' : 'none';
        }
    </script>
</x-guest-layout>