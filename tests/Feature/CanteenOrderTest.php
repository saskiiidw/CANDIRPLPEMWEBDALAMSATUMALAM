<?php

namespace Tests\Feature;

use App\Livewire\CanteenOrder;
use App\Livewire\OrderStatusTracker;
use App\Livewire\SellerDashboard;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use Tests\TestCase;

class CanteenOrderTest extends TestCase
{
    use RefreshDatabase;

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function makeSeller(): User
    {
        return User::factory()->penjual()->create();
    }

    private function makeMahasiswa(): User
    {
        return User::factory()->create();
    }

    private function makeMenu(User $seller, array $attrs = []): Menu
    {
        return Menu::factory()->create(array_merge([
            'seller_id'            => $seller->id,
            'price'                => 10000,
            'stock'                => 10,
            'cooking_time_minutes' => 10,
            'is_active'            => true,
        ], $attrs));
    }

    // ─── Tests ────────────────────────────────────────────────────────────────

    public function test_menu_can_be_loaded_for_a_seller(): void
    {
        $seller    = $this->makeSeller();
        $mahasiswa = $this->makeMahasiswa();
        $menu      = $this->makeMenu($seller);

        $this->actingAs($mahasiswa);

        Livewire::test(CanteenOrder::class, ['sellerId' => $seller->id])
            ->assertViewHas('menus');
    }

    public function test_checkout_creates_order_and_decrements_stock(): void
    {
        $seller    = $this->makeSeller();
        $mahasiswa = $this->makeMahasiswa();
        $menu      = $this->makeMenu($seller, ['stock' => 5]);

        $this->actingAs($mahasiswa);

        $component = Livewire::test(CanteenOrder::class, ['sellerId' => $seller->id]);

        // Update cart langsung melalui properti (Livewire akan trigger updatedCart)
        $component->set("cart.{$menu->id}", 2)
            ->call('checkout')
            ->assertSet('showPaymentModal', true)
            ->assertSet('stockError', '');

        // Pesanan harus tersimpan dengan status menunggu pembayaran
        $this->assertDatabaseHas('orders', [
            'buyer_id'  => $mahasiswa->id,
            'seller_id' => $seller->id,
            'status'    => 'menunggu_pembayaran',
        ]);

        // Stok harus berkurang sebanyak qty yang dipesan
        $menu->refresh();
        $this->assertSame(3, $menu->stock); // 5 - 2 = 3
    }

    public function test_checkout_fails_if_stock_is_insufficient(): void
    {
        $seller    = $this->makeSeller();
        $mahasiswa = $this->makeMahasiswa();
        // Menu yang sudah habis stoknya (stock=0)
        $menu = $this->makeMenu($seller, ['stock' => 0]);

        $this->actingAs($mahasiswa);

        // updatedCart() akan menolak qty apapun karena stock=0 dan cart akan dikosongkan.
        // Sehingga ketika checkout() dipanggil, cart kosong dan stockError akan diset.
        Livewire::test(CanteenOrder::class, ['sellerId' => $seller->id])
            ->set("cart.{$menu->id}", 1)
            ->call('checkout')
            ->assertSet('showPaymentModal', false);

        $this->assertDatabaseMissing('orders', ['buyer_id' => $mahasiswa->id]);

        // Stok tidak boleh berubah (tetap 0)
        $menu->refresh();
        $this->assertSame(0, $menu->stock);
    }

    public function test_simulate_payment_changes_status_to_diterima(): void
    {
        $seller    = $this->makeSeller();
        $mahasiswa = $this->makeMahasiswa();
        $menu      = $this->makeMenu($seller);

        $this->actingAs($mahasiswa);

        $component = Livewire::test(CanteenOrder::class, ['sellerId' => $seller->id])
            ->set("cart.{$menu->id}", 1)
            ->call('checkout');

        $orderId = $component->get('currentOrderId');

        $component
            ->call('simulatePayment')
            ->assertSet('showPaymentModal', false)
            ->assertSet('cart', []);

        $this->assertDatabaseHas('orders', [
            'id'     => $orderId,
            'status' => 'diterima',
        ]);

        $this->assertDatabaseHas('payments', [
            'order_id' => $orderId,
            'status'   => 'lunas',
        ]);
    }

    public function test_cancel_order_restores_stock(): void
    {
        $seller    = $this->makeSeller();
        $mahasiswa = $this->makeMahasiswa();
        $menu      = $this->makeMenu($seller, ['stock' => 5]);

        $this->actingAs($mahasiswa);

        $component = Livewire::test(CanteenOrder::class, ['sellerId' => $seller->id])
            ->set("cart.{$menu->id}", 2)
            ->call('checkout');

        $orderId = $component->get('currentOrderId');

        // Stok sekarang sudah dikurangi menjadi 3
        $menu->refresh();
        $this->assertSame(3, $menu->stock);

        // Batalkan pesanan (status masih menunggu_pembayaran)
        $component->call('cancelOrder', $orderId);

        $this->assertDatabaseHas('orders', [
            'id'     => $orderId,
            'status' => 'dibatalkan',
        ]);

        // Stok harus kembali ke 5
        $menu->refresh();
        $this->assertSame(5, $menu->stock);
    }

    public function test_cannot_cancel_order_after_being_processed(): void
    {
        $seller    = $this->makeSeller();
        $mahasiswa = $this->makeMahasiswa();
        $menu      = $this->makeMenu($seller);

        // Buat pesanan yang sudah berstatus 'diproses'
        $order = Order::factory()->diterima()->create([
            'buyer_id'  => $mahasiswa->id,
            'seller_id' => $seller->id,
        ]);
        OrderItem::factory()->create([
            'order_id'           => $order->id,
            'menu_id'            => $menu->id,
            'menu_name_snapshot' => $menu->name,
            'price_snapshot'     => $menu->price,
            'quantity'           => 1,
            'subtotal'           => $menu->price,
        ]);
        $order->update(['status' => 'diproses']);

        $this->actingAs($mahasiswa);

        Livewire::test(CanteenOrder::class, ['sellerId' => $seller->id])
            ->call('cancelOrder', $order->id);

        // Status harus tetap 'diproses', tidak berubah ke 'dibatalkan'
        $this->assertDatabaseHas('orders', [
            'id'     => $order->id,
            'status' => 'diproses',
        ]);
    }

    public function test_confirm_received_changes_status_to_selesai(): void
    {
        $seller    = $this->makeSeller();
        $mahasiswa = $this->makeMahasiswa();

        $order = Order::factory()->create([
            'buyer_id'  => $mahasiswa->id,
            'seller_id' => $seller->id,
            'status'    => 'siap_diambil',
        ]);

        $this->actingAs($mahasiswa);

        Livewire::test(OrderStatusTracker::class, ['order' => $order])
            ->call('confirmReceived');

        $this->assertDatabaseHas('orders', [
            'id'     => $order->id,
            'status' => 'selesai',
        ]);
    }

    public function test_eta_calculation_is_reasonable(): void
    {
        $seller    = $this->makeSeller();
        $mahasiswa = $this->makeMahasiswa();
        $menu      = $this->makeMenu($seller, ['cooking_time_minutes' => 15]);

        $this->actingAs($mahasiswa);

        $component = Livewire::test(CanteenOrder::class, ['sellerId' => $seller->id])
            ->set("cart.{$menu->id}", 1);

        $eta = $component->instance()->calculateEta();

        // ETA harus minimal setara dengan cooking_time_minutes menu
        $this->assertGreaterThanOrEqual(15, $eta);
    }

    public function test_audit_log_is_written_on_order_creation(): void
    {
        $seller    = $this->makeSeller();
        $mahasiswa = $this->makeMahasiswa();
        $menu      = $this->makeMenu($seller);

        $this->actingAs($mahasiswa);

        Livewire::test(CanteenOrder::class, ['sellerId' => $seller->id])
            ->set("cart.{$menu->id}", 1)
            ->call('checkout');

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $mahasiswa->id,
            'action'  => 'order.created',
        ]);
    }

    public function test_audit_log_is_written_on_payment(): void
    {
        $seller    = $this->makeSeller();
        $mahasiswa = $this->makeMahasiswa();
        $menu      = $this->makeMenu($seller);

        $this->actingAs($mahasiswa);

        $component = Livewire::test(CanteenOrder::class, ['sellerId' => $seller->id])
            ->set("cart.{$menu->id}", 1)
            ->call('checkout');

        $component->call('simulatePayment');

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $mahasiswa->id,
            'action'  => 'order.paid',
        ]);
    }
}
