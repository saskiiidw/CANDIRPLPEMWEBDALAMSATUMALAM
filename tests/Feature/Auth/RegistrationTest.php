<?php

namespace Tests\Feature\Auth;

use App\Livewire\Auth\RegisterForm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertOk();
    }

    public function test_mahasiswa_can_register(): void
    {
        Livewire::test(RegisterForm::class)
            ->set('name', 'Budi Santoso')
            ->set('email', 'budi@mhs.unsoed.ac.id')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->set('role', 'mahasiswa')
            ->call('register')
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard'));

        $user = User::where('email', 'budi@mhs.unsoed.ac.id')->first();
        $this->assertNotNull($user);
        $this->assertSame('mahasiswa', $user->role);
        $this->assertTrue($user->is_verified);
    }

    public function test_penjual_can_register_with_store_name(): void
    {
        Livewire::test(RegisterForm::class)
            ->set('name', 'Mas Eko')
            ->set('email', 'eko@kantin.unsoed.ac.id')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->set('role', 'penjual')
            ->set('store_name', 'Warung Mas Eko')
            ->call('register')
            ->assertHasNoErrors()
            ->assertRedirect(route('seller.pending'));

        $user = User::where('email', 'eko@kantin.unsoed.ac.id')->first();
        $this->assertNotNull($user);
        $this->assertSame('penjual', $user->role);
        $this->assertSame('Warung Mas Eko', $user->store_name);
        $this->assertFalse($user->is_verified);
    }

    public function test_penjual_registration_fails_without_store_name(): void
    {
        Livewire::test(RegisterForm::class)
            ->set('name', 'Mas Eko')
            ->set('email', 'eko@kantin.unsoed.ac.id')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->set('role', 'penjual')
            ->set('store_name', '')
            ->call('register')
            ->assertHasErrors(['store_name']);

        $this->assertDatabaseMissing('users', ['email' => 'eko@kantin.unsoed.ac.id']);
    }

    public function test_role_is_actually_persisted_to_database(): void
    {
        // Verifikasi bug mass-assignment sudah diperbaiki:
        // kolom 'role' harus tersimpan, bukan default 'mahasiswa'.
        Livewire::test(RegisterForm::class)
            ->set('name', 'Pak Dosen')
            ->set('email', 'dosen@ft.unsoed.ac.id')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->set('role', 'penjual')
            ->set('store_name', 'Kantin Pak Dosen')
            ->call('register');

        $this->assertDatabaseHas('users', [
            'email' => 'dosen@ft.unsoed.ac.id',
            'role'  => 'penjual',
        ]);
    }
}

