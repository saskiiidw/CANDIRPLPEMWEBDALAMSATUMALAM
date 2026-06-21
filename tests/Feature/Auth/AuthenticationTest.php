<?php

namespace Tests\Feature\Auth;

use App\Livewire\Auth\LoginForm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $this->get('/login')->assertOk();
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        Livewire::test(LoginForm::class)
            ->set('email', $user->email)
            ->set('password', 'password')
            ->call('login')
            ->assertHasNoErrors()
            ->assertRedirect(route('home.student'));

        $this->assertAuthenticated();
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        Livewire::test(LoginForm::class)
            ->set('email', $user->email)
            ->set('password', 'wrong-password')
            ->call('login')
            ->assertNoRedirect();

        $this->assertGuest();
    }

    public function test_login_is_locked_after_5_failed_attempts(): void
    {
        $user = User::factory()->create();

        $component = Livewire::test(LoginForm::class);

        // 5 percobaan gagal pertama — tidak terblokir dulu
        for ($i = 0; $i < 5; $i++) {
            $component
                ->set('email', $user->email)
                ->set('password', 'wrong')
                ->call('login');
        }

        // Percobaan ke-6 harus terblokir RateLimiter
        $component
            ->set('email', $user->email)
            ->set('password', 'password')
            ->call('login')
            ->assertNoRedirect();

        // errorMessage harus muncul (akun terkunci)
        $this->assertTrue(
            RateLimiter::tooManyAttempts(
                strtolower($user->email).'|127.0.0.1',
                5
            )
        );

        $this->assertGuest();

        // Bersihkan limiter untuk test berikutnya
        RateLimiter::clear(strtolower($user->email).'|127.0.0.1');
    }

    public function test_inactive_user_cannot_login(): void
    {
        $user = User::factory()->create(['is_active' => false]);

        Livewire::test(LoginForm::class)
            ->set('email', $user->email)
            ->set('password', 'password')
            ->call('login')
            ->assertNoRedirect();

        $this->assertGuest();
    }

    public function test_unverified_seller_cannot_login(): void
    {
        $seller = User::factory()->penjualBelumDiverifikasi()->create();

        Livewire::test(LoginForm::class)
            ->set('email', $seller->email)
            ->set('password', 'password')
            ->call('login')
            ->assertNoRedirect();

        $this->assertGuest();
    }

    public function test_admin_is_redirected_to_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        Livewire::test(LoginForm::class)
            ->set('email', $admin->email)
            ->set('password', 'password')
            ->call('login')
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_seller_is_redirected_to_seller_dashboard(): void
    {
        $seller = User::factory()->penjual()->create();

        Livewire::test(LoginForm::class)
            ->set('email', $seller->email)
            ->set('password', 'password')
            ->call('login')
            ->assertRedirect(route('seller.dashboard'));
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $component = \Livewire\Volt\Volt::test('layout.navigation');

        $component->call('logout');

        $component
            ->assertHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
    }
}

