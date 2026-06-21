<?php

namespace Tests\Feature\Livewire;

use App\Livewire\SellerVerification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SellerVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_approve_seller()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $pendingSeller = User::factory()->create([
            'role' => 'penjual', 
            'is_verified' => false
        ]);

        Livewire::actingAs($admin)
            ->test(SellerVerification::class)
            ->call('approve', $pendingSeller->id);

        $this->assertTrue($pendingSeller->fresh()->is_verified);
    }

    public function test_admin_can_reject_seller_with_reason()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $pendingSeller = User::factory()->create([
            'role' => 'penjual', 
            'is_verified' => false
        ]);

        $reason = "Foto KTP buram";

        Livewire::actingAs($admin)
            ->test(SellerVerification::class)
            ->call('reject', $pendingSeller->id, $reason);

        $this->assertEquals($reason, $pendingSeller->fresh()->rejection_reason);
        $this->assertFalse($pendingSeller->fresh()->is_verified);
    }

    public function test_non_admin_cannot_access_verification_page()
    {
        $student = User::factory()->create(['role' => 'mahasiswa']);

        Livewire::actingAs($student)
            ->test(SellerVerification::class)
            ->assertForbidden(); // Memastikan RBAC berjalan
    }
}
