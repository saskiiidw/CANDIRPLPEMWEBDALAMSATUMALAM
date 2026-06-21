<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CanteenSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Admin ─────────────────────────────────────────────────────────
        User::factory()->admin()->create([
            'name'  => 'Admin Kantin',
            'email' => 'admin@unsoed.ac.id',
        ]);

        // ── 2. Satu Penjual ───────────────────────────────────────────────────
        $penjual = User::factory()->penjual()->create([
            'name'        => 'Mas Eddy Santoso',
            'email'       => 'eddy@kantin.unsoed.ac.id',
            'store_name'  => 'Kantin Mas Eddy',
            'is_verified' => true,
            'is_active'   => true,
        ]);

        // ── 3. Mahasiswa ──────────────────────────────────────────────────────
        $mahasiswa = User::factory()->create([
            'name'        => 'Astria Dewi',
            'email'       => 'astria@mhs.unsoed.ac.id',
            'role'        => 'mahasiswa',
            'is_verified' => true,
            'is_active'   => true,
        ]);

        // ── 4. Menu Makanan Indonesia ─────────────────────────────────────────
        $menus = [
            [
                'name'                 => 'Nasi Goreng Spesial',
                'description'          => 'Nasi goreng dengan telur mata sapi, ayam suwir, udang, dan kerupuk. Pilihan: pedas/tidak pedas.',
                'category'             => 'makanan_berat',
                'price'                => 15000,
                'stock'                => 20,
                'cooking_time_minutes' => 10,
            ],
            [
                'name'                 => 'Mie Ayam Bakso',
                'description'          => 'Mie kuning lembut dengan topping ayam cincang bumbu kecap, bakso sapi kenyal, dan kuah kaldu gurih.',
                'category'             => 'makanan_berat',
                'price'                => 13000,
                'stock'                => 15,
                'cooking_time_minutes' => 8,
            ],
            [
                'name'                 => 'Soto Ayam Lamongan',
                'description'          => 'Soto kuah bening khas Lamongan dengan ayam suwir, telur rebus, tauge, soun, dan koya gurih renyah.',
                'category'             => 'makanan_berat',
                'price'                => 14000,
                'stock'                => 18,
                'cooking_time_minutes' => 10,
            ],
            [
                'name'                 => 'Nasi Padang Lengkap',
                'description'          => 'Nasi putih hangat dengan rendang daging, gulai ayam, perkedel, tempe balado, dan sayur nangka.',
                'category'             => 'makanan_berat',
                'price'                => 20000,
                'stock'                => 12,
                'cooking_time_minutes' => 5,
            ],
            [
                'name'                 => 'Gado-Gado',
                'description'          => 'Sayuran rebus segar (kangkung, tauge, kacang panjang, kentang, telur) disiram saus kacang kental dengan lontong.',
                'category'             => 'makanan_berat',
                'price'                => 12000,
                'stock'                => 10,
                'cooking_time_minutes' => 7,
            ],
            [
                'name'                 => 'Ayam Geprek Crispy',
                'description'          => 'Ayam goreng tepung crispy digeprek dengan sambal bawang merah ekstra pedas. Disajikan dengan nasi putih dan lalapan.',
                'category'             => 'makanan_berat',
                'price'                => 16000,
                'stock'                => 25,
                'cooking_time_minutes' => 12,
            ],
            [
                'name'                 => 'Pecel Lele',
                'description'          => 'Lele goreng garing dengan sambal terasi tomat, lalapan segar (timun, kemangi, kol), dan nasi putih pulen.',
                'category'             => 'makanan_berat',
                'price'                => 14000,
                'stock'                => 15,
                'cooking_time_minutes' => 10,
            ],
            [
                'name'                 => 'Risoles Sayur',
                'description'          => 'Kulit dadar tipis berisi tumisan wortel, buncis, dan bihun yang diberi bumbu gurih, dibalut tepung roti, lalu digoreng.',
                'category'             => 'makanan_ringan',
                'price'                => 3000,
                'stock'                => 30,
                'cooking_time_minutes' => 3,
            ],
            [
                'name'                 => 'Pisang Goreng Keju',
                'description'          => 'Pisang kepok pilihan digoreng renyah dengan balutan tepung, ditaburi keju parut leleh. Manis dan gurih.',
                'category'             => 'makanan_ringan',
                'price'                => 8000,
                'stock'                => 20,
                'cooking_time_minutes' => 5,
            ],
            [
                'name'                 => 'Tempe Mendoan',
                'description'          => 'Tempe tipis berlapis tepung bumbu dengan daun bawang, digoreng setengah matang khas Banyumas. Renyah di luar, lembut di dalam.',
                'category'             => 'makanan_ringan',
                'price'                => 5000,
                'stock'                => 40,
                'cooking_time_minutes' => 5,
            ],
            [
                'name'                 => 'Es Teh Manis',
                'description'          => 'Teh hitam pilihan diseduh kuat, diberi gula aren cair, dan disajikan dengan es batu yang banyak. Segar dan melegakan.',
                'category'             => 'minuman',
                'price'                => 5000,
                'stock'                => 50,
                'cooking_time_minutes' => 2,
            ],
            [
                'name'                 => 'Es Jeruk Peras',
                'description'          => 'Jeruk siam segar diperas langsung, tanpa pengawet, diberi sedikit gula dan es batu. Vitamin C alami.',
                'category'             => 'minuman',
                'price'                => 7000,
                'stock'                => 30,
                'cooking_time_minutes' => 3,
            ],
            [
                'name'                 => 'Jus Alpukat',
                'description'          => 'Alpukat Garut pilihan diblender dengan susu kental manis dan sedikit cokelat bubuk. Kental, creamy, mengenyangkan.',
                'category'             => 'minuman',
                'price'                => 10000,
                'stock'                => 15,
                'cooking_time_minutes' => 5,
            ],
            [
                'name'                 => 'Es Cendol',
                'description'          => 'Cendol tepung beras hijau pandan, santan kelapa segar, dan gula merah Jawa cair. Kesegaran tradisional yang autentik.',
                'category'             => 'minuman',
                'price'                => 8000,
                'stock'                => 0, // habis
                'cooking_time_minutes' => 3,
            ],
        ];

        $createdMenus = [];
        foreach ($menus as $menu) {
            $createdMenus[$menu['name']] = Menu::create(array_merge($menu, [
                'seller_id' => $penjual->id,
                'is_active' => true,
            ]));
        }

        // ── 5. Riwayat Pesanan (untuk tampilan Order History) ─────────────────

        // Pesanan 1: Selesai — Nasi Goreng + Es Teh Manis + Tempe Mendoan
        $order1 = Order::create([
            'buyer_id'   => $mahasiswa->id,
            'seller_id'  => $penjual->id,
            'total_price'=> 25000,
            'eta_minutes'=> 12,
            'status'     => 'selesai',
            'note'       => 'Tidak pedas ya kak',
            'created_at' => Carbon::now()->subDays(3),
        ]);
        OrderItem::create([
            'order_id'           => $order1->id,
            'menu_id'            => $createdMenus['Nasi Goreng Spesial']->id,
            'menu_name_snapshot' => 'Nasi Goreng Spesial',
            'price_snapshot'     => 15000,
            'quantity'           => 1,
            'subtotal'           => 15000,
            'notes'              => 'Tidak pedas',
        ]);
        OrderItem::create([
            'order_id'           => $order1->id,
            'menu_id'            => $createdMenus['Es Teh Manis']->id,
            'menu_name_snapshot' => 'Es Teh Manis',
            'price_snapshot'     => 5000,
            'quantity'           => 1,
            'subtotal'           => 5000,
        ]);
        OrderItem::create([
            'order_id'           => $order1->id,
            'menu_id'            => $createdMenus['Tempe Mendoan']->id,
            'menu_name_snapshot' => 'Tempe Mendoan',
            'price_snapshot'     => 5000,
            'quantity'           => 1,
            'subtotal'           => 5000,
        ]);
        Payment::create([
            'order_id' => $order1->id,
            'amount'   => 25000,
            'method'   => 'dummy_payment',
            'status'   => 'lunas',
            'paid_at'  => Carbon::now()->subDays(3),
        ]);

        // Pesanan 2: Dibatalkan — Ayam Geprek + Es Jeruk
        $order2 = Order::create([
            'buyer_id'   => $mahasiswa->id,
            'seller_id'  => $penjual->id,
            'total_price'=> 23000,
            'eta_minutes'=> 15,
            'status'     => 'dibatalkan',
            'note'       => null,
            'created_at' => Carbon::now()->subDays(7),
        ]);
        OrderItem::create([
            'order_id'           => $order2->id,
            'menu_id'            => $createdMenus['Ayam Geprek Crispy']->id,
            'menu_name_snapshot' => 'Ayam Geprek Crispy',
            'price_snapshot'     => 16000,
            'quantity'           => 1,
            'subtotal'           => 16000,
        ]);
        OrderItem::create([
            'order_id'           => $order2->id,
            'menu_id'            => $createdMenus['Es Jeruk Peras']->id,
            'menu_name_snapshot' => 'Es Jeruk Peras',
            'price_snapshot'     => 7000,
            'quantity'           => 1,
            'subtotal'           => 7000,
        ]);
    }
}