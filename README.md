# SMARTCANTEEN 🍱🥤
**Sistem Pemesanan Makanan Kantin Online Antarmahasiswa**
*Platform digital berbasis website responsif untuk memfasilitasi pemesanan makanan secara daring dan efisien di lingkungan Universitas Jenderal Soedirman.*

---

## 📌 Tentang SMARTCANTEEN

**SMARTCANTEEN** adalah platform digital yang dirancang untuk mengatasi antrean panjang di kantin kampus Universitas Jenderal Soedirman. Sistem ini menghubungkan tiga peran pengguna utama: **Mahasiswa (Pembeli)**, **Penjual Kantin (Merchant)**, dan **Admin Sistem** dalam satu platform monolitik yang reaktif.

Dengan memanfaatkan **TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)** dan **Laravel Reverb (WebSocket)**, platform ini memungkinkan interaksi real-time tanpa perlu memuat ulang (reload) halaman—mulai dari pembaruan stok menu secara langsung hingga notifikasi status pesanan instan.

---

## 🚀 Fitur Utama

Sistem ini diimplementasikan dengan fitur-fitur utama sebagai berikut:

1. **Registrasi dan Login dengan Keamanan Lockout:** Menyediakan formulir registrasi dan login berbasis Livewire untuk semua jenis pengguna (mahasiswa, penjual, admin). Validasi format email, keunikan email di database MySQL, dan kesesuaian password dilakukan secara live menggunakan `wire:model.live` tanpa submit terpisah. Sistem menerapkan lockout otomatis selama 15 menit setelah 5 kali percobaan login gagal. Setelah berhasil login, pengguna diarahkan ke dashboard sesuai perannya via `wire:navigate` tanpa reload penuh.
2. **Pemesanan Makanan Online dengan Validasi Stok & ETA:** Mahasiswa dapat menelusuri daftar menu beserta stok terkini, memilih item, mengatur jumlah, dan menambahkan catatan khusus. Setiap perubahan jumlah pesanan memicu method Livewire secara reaktif untuk memvalidasi stok di MySQL dan memperbarui ringkasan total harga serta estimasi waktu (ETA) langsung pada DOM tanpa reload halaman. Menu dengan stok habis otomatis dinonaktifkan agar tidak bisa dipesan. Setelah konfirmasi, data tersimpan dengan status 'Menunggu Pembayaran' dan modal simulasi pembayaran ditampilkan.
3. **Simulasi Pembayaran Digital Internal:** Sistem menyediakan mekanisme simulasi pembayaran yang dieksekusi sepenuhnya melalui state Livewire di server tanpa keterlibatan payment gateway eksternal. Mahasiswa menekan tombol 'Simulasikan Pembayaran' pada modal Alpine.js; method Livewire mengubah status pesanan dari 'Menunggu Pembayaran' menjadi 'Diterima', menyimpan catatan transaksi ke tabel payments, dan mengurangi stok menu secara permanen. Event broadcast kemudian dikirim ke dashboard penjual melalui Laravel Reverb secara real-time.
4. **Dashboard Penjual Real-Time:** Penjual memiliki dashboard berbasis Livewire yang merender ulang daftar pesanan masuk secara otomatis setiap kali event baru diterima dari Laravel Reverb tanpa perlu me-refresh halaman. Penjual dapat mengubah status pesanan secara bertahap (Diterima → Diproses → Siap Diambil → Selesai) atau menolak pesanan. Setiap perubahan status dipancarkan kembali ke sisi mahasiswa melalui WebSocket sehingga status pesanan mahasiswa juga diperbarui secara live.
5. **Pelacakan Status Pesanan Real-Time:** Mahasiswa dapat memantau status pesanan aktifnya secara real-time melalui komponen `OrderStatusTracker`. Listener Livewire menangkap broadcast event dari Laravel Reverb dan menampilkan banner notifikasi in-app (misalnya 'Pesanan Anda sudah siap diambil!') langsung pada halaman dalam waktu kurang dari 2 detik. Mahasiswa juga dapat mengonfirmasi penerimaan pesanan melalui tombol `confirmReceived()` yang mengubah status akhir menjadi 'Selesai' dan mencatatnya ke riwayat transaksi.
6. **Riwayat Pesanan & Laporan Transaksi Penjual:** Mahasiswa dapat mengakses riwayat seluruh pesanan yang pernah dilakukan beserta detail status, total harga, item yang dipesan, dan tanggal transaksi melalui komponen `OrderHistory`. Penjual memiliki akses ke laporan transaksi harian dan mingguan melalui komponen `SalesReport` yang merangkum total pendapatan, jumlah pesanan, dan menu terlaris membantu evaluasi penjualan dan perencanaan stok untuk hari berikutnya.
7. **Verifikasi Penjual, Manajemen Pengguna & Audit Log:** Admin sistem dapat meninjau dan memverifikasi akun penjual baru menyetujui atau menolak pendaftaran berdasarkan data yang diberikan melalui komponen `SellerVerification`. Selain itu, admin memiliki akses ke halaman manajemen pengguna (`UserManagement`) untuk melihat seluruh daftar akun, mengaktifkan/menonaktifkan akun, serta memantau aktivitas. Seluruh aksi kritis (login, register, simulasi pembayaran, perubahan status, verifikasi) tercatat otomatis oleh `AuditLogger` service dengan retensi minimal 90 hari.
8. **Pembatalan Pesanan & Keamanan Akses Berlapis:** Penjual dapat membatalkan pesanan selama status masih 'Menunggu Pembayaran' atau 'Diterima' melalui method `cancelOrder()`. Pembatalan mengubah status menjadi 'Dibatalkan' dan mengembalikan stok menu secara otomatis. Keamanan akses ditegakkan secara berlapis: route menggunakan middleware `EnsureUserHasRole`, sedangkan setiap komponen Livewire menerapkan trait `AuthorizesRole` dengan pengecekan `authorizeRole()` pada method `mount()` memastikan tidak ada privilege escalation antar peran.

---

## 🛠️ Spesifikasi Teknologi

| Kategori | Teknologi | Keterangan / Versi |
| :--- | :--- | :--- |
| **Bahasa Pemrograman** | PHP, JavaScript | PHP 8.3 / JS (ES2022) |
| **Backend Framework** | Laravel | Versi 13.8 |
| **Frontend Reactive** | Livewire & Alpine.js | Livewire 3.6.4 / Alpine.js |
| **CSS Framework** | Tailwind CSS | Utility-First Responsive Design |
| **Server Websocket** | Laravel Reverb | Reverb 1.0 (Native WebSocket Server) |
| **Database** | MySQL | Relational Database |
| **Ekspor PDF** | DOMPDF | Versi 3.1 |

---

## 📖 Panduan Penggunaan Aplikasi (Cara Pakai)

### 👨‍🎓 1. Alur Penggunaan untuk Mahasiswa (Pembeli)
* **Pendaftaran Akun:** Akses halaman utama, klik **Masuk** lalu pilih registrasi untuk Mahasiswa menggunakan email akademik/umum.
* **Memilih Menu:** Setelah masuk ke halaman *Student Home*, telusuri daftar makanan/minuman yang aktif. Tentukan kuantitas dan tambahkan catatan khusus (misal: *"Tidak pakai pedas"*).
* **Checkout & Simulasi Pembayaran:** Klik tombol pesan. Pesanan akan tercatat sebagai `Menunggu Pembayaran`. Tekan tombol **Simulasikan Pembayaran** pada modal yang muncul. State pesanan akan berubah menjadi `Diterima` dan stok penjual akan langsung berkurang.
* **Melacak Pesanan:** Masuk ke Halaman **Status Pesanan**. Anda dapat memantau proses memasak oleh penjual secara real-time tanpa perlu me-refresh halaman.
* **Pengambilan Makanan & Konfirmasi:** Begitu status berubah menjadi `Siap Diambil`, segera datangi stand kantin terkait. Setelah makanan diterima secara fisik, klik tombol **Konfirmasi Penerimaan** untuk menyelesaikan transaksi dan mencatatnya ke dalam **Riwayat Pesanan**.

---

### 🏪 2. Alur Penggunaan untuk Penjual (Merchant)
* **Pendaftaran Mitra:** Registrasi akun melalui tautan khusus **Daftar Penjual** dan lengkapi detail profil toko. Akun baru harus menunggu persetujuan verifikasi dari Admin sebelum dapat masuk ke sistem.
* **Dashboard Toko:** Setelah disetujui, masuk ke Dashboard Penjual. Setiap ada pesanan masuk yang telah dibayar oleh mahasiswa, daftar pesanan akan muncul secara real-time disertai bunyi notifikasi/efek reaktif.
* **Memproses Pesanan:** Ubah status pesanan secara berkala:
  1. Klik **Proses** saat makanan mulai dimasak (status di sisi mahasiswa berubah menjadi `Diproses`).
  2. Klik **Siap Diambil** setelah makanan matang dan siap diserahkan (status mahasiswa berubah menjadi `Siap Diambil`).
  3. Transaksi akan otomatis ditandai `Selesai` setelah pembeli menekan konfirmasi di ponsel mereka.
* **Manajemen Inventaris:** Masuk ke tab **Manajemen Menu** untuk menambah menu baru, mengubah harga, memperbarui foto, atau menonaktifkan menu jika bahan baku habis.
* **Melihat Laporan:** Akses menu laporan keuangan harian dan mingguan untuk menganalisis menu terlaris serta mengunduh rekap penjualan berformat PDF.

---

### 👑 3. Alur Penggunaan untuk Admin Sistem
* **Verifikasi Penjual Baru:** Akses panel admin pada tab **Verifikasi Penjual** untuk meninjau berkas pendaftaran warung/toko baru, lalu klik **Setujui** atau **Tolak**.
* **Manajemen Pengguna:** Cari, filter, dan kelola status aktif/nonaktif akun seluruh pengguna (mahasiswa & penjual) demi menjaga keamanan platform.
* **Memantau Audit Log:** Masuk ke Halaman **Audit Log** untuk meninjau log keamanan terpusat, merekam aktivitas krusial seperti percobaan login gagal, perubahan data transaksi, dan riwayat pemesanan mencurigakan.

---

## 💻 Panduan Instalasi Lokal

1. **Clone Repository:**
   ```bash
   git clone https://github.com/saskiiidw/CANDIRPLPEMWEBDALAMSATUMALAM.git
   cd CANDIRPLPEMWEBDALAMSATUMALAM
   ```

2. **Instalasi Dependensi PHP & JavaScript:**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment:**
   Salin berkas `.env.example` menjadi `.env` lalu sesuaikan kredensial database Anda:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Migrasi Database & Seeding:**
   ```bash
   php artisan migrate --seed
   ```

5. **Jalankan Aplikasi:**
   Jalankan server Laravel, aset Vite, dan server WebSocket Reverb pada tiga terminal terpisah:
   * **Terminal 1 (Laravel Server):**
     ```bash
     php artisan serve
     ```
   * **Terminal 2 (Vite Build):**
     ```bash
     npm run dev
     ```
   * **Terminal 3 (Laravel Reverb WebSocket):**
     ```bash
     php artisan reverb:start
     ```
