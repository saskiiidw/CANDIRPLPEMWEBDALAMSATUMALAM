<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Seeder;

class CanteenSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->admin()->create([
            'name' => 'Admin Kantin',
            'email' => 'admin@unsoed.ac.id',
        ]);

        $penjual = User::factory()->penjual()->create([
            'name' => 'Mas Eddy',
            'email' => 'eddsy@kantin.unsoed.ac.id',
            'store_name' => 'Kantin Mas Eddy',
        ]);

        $mahasiswa = User::factory()->create([
            'name' => 'Astria',
            'email' => 'astria@mhs.unsoed.ac.id',
        ]);

        Menu::factory()->count(5)->create(['seller_id' => $penjual->id]);
    }
}