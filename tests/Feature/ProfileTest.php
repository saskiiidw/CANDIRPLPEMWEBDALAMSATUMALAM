<?php

namespace Tests\Feature;

use App\Livewire\ProfileEditor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');
        $response->assertRedirect(route('profile.edit'));

        $this->actingAs($user)->get(route('profile.edit'))->assertOk();
    }

    public function test_user_can_update_name_and_phone(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(ProfileEditor::class)
            ->set('name', 'Nama Baru')
            ->set('phone', '08123456789')
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('profile-updated');

        $user->refresh();
        $this->assertSame('Nama Baru', $user->name);
        $this->assertSame('08123456789', $user->phone);
    }

    public function test_seller_must_fill_store_name(): void
    {
        $seller = User::factory()->penjual()->create();
        $this->actingAs($seller);

        Livewire::test(ProfileEditor::class)
            ->set('name', 'Mas Eko')
            ->set('store_name', '')
            ->call('save')
            ->assertHasErrors(['store_name']);
    }

    public function test_seller_can_update_store_name(): void
    {
        $seller = User::factory()->penjual()->create();
        $this->actingAs($seller);

        Livewire::test(ProfileEditor::class)
            ->set('name', 'Mas Eko')
            ->set('store_name', 'Kantin Mas Eko Baru')
            ->call('save')
            ->assertHasNoErrors();

        $seller->refresh();
        $this->assertSame('Kantin Mas Eko Baru', $seller->store_name);
    }
}

