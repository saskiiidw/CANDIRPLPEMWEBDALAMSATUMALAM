<?php

namespace App\Livewire;

use App\Events\OrderStatusUpdated;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class CanteenOrder extends Component
{
    use WithPagination;

    public string $search = '';
    public string $category = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategory(): void
    {
        $this->resetPage();
    }
    public int $sellerId;

    /** @var \Illuminate\Support\Collection<int, Menu> */
    public $menus;

    public array $cart = []; // [menu_id => quantity]
    public array $itemNotes = []; // [menu_id => notes]
    public ?string $note = null;

    public ?int $currentOrderId = null;
    public bool $showPaymentModal = false;
    public string $stockError = '';
    public ?int $etaMinutes = null;

    public string $step = 'menu'; // menu|checkout|customize
    public ?int $selectedMenuId = null;
    public int $customizeQuantity = 1;
    public string $customizeNote = '';

    public function mount(?int $seller = null, ?int $sellerId = null): void
    {
        $this->sellerId = $seller ?? $sellerId ?? throw new \InvalidArgumentException('Seller ID is required');
        $this->loadMenus();
        $this->cart = session('cart', []);
        $this->itemNotes = session('itemNotes', []);
    }

    public function loadMenus(): void
    {
        $this->menus = Menu::where('seller_id', $this->sellerId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function syncSession(): void
    {
        session(['cart' => $this->cart]);
        session(['itemNotes' => $this->itemNotes]);
    }

    /**
     * Dipanggil otomatis Livewire setiap properti $cart berubah (wire:model.live).
     * Validasi stok real-time ke MySQL, mencegah quantity melebihi stok aktual.
     */
    public function updatedCart($value, $key): void
    {
        $menuId = (int) $key;
        $qty = (int) $value;

        if ($qty <= 0) {
            unset($this->cart[$menuId]);
            unset($this->itemNotes[$menuId]);
            $this->stockError = '';
            $this->syncSession();
            return;
        }

        $menu = Menu::find($menuId);

        if (! $menu || ! $menu->isAvailable($qty)) {
            $this->stockError = "Stok tidak mencukupi untuk menu \"{$menu?->name}\".";
            $this->cart[$menuId] = $menu?->stock ?? 0;
            $this->syncSession();
            return;
        }

        $this->stockError = '';
        $this->syncSession();
    }

    public function openCustomize(int $menuId): void
    {
        $this->selectedMenuId = $menuId;
        $this->customizeQuantity = $this->cart[$menuId] ?? 1;
        $this->customizeNote = $this->itemNotes[$menuId] ?? '';
        $this->step = 'customize';
    }

    public function closeCustomize(): void
    {
        $this->step = 'menu';
    }

    public function incrementCustomize(): void
    {
        $menu = Menu::find($this->selectedMenuId);
        if ($menu && $this->customizeQuantity < $menu->stock) {
            $this->customizeQuantity++;
        }
    }

    public function decrementCustomize(): void
    {
        if ($this->customizeQuantity > 1) {
            $this->customizeQuantity--;
        }
    }

    public function addCustomizeToCart(): void
    {
        $menu = Menu::find($this->selectedMenuId);
        if (!$menu) return;

        if (!$menu->isAvailable($this->customizeQuantity)) {
            $this->stockError = "Stok tidak mencukupi untuk menu \"{$menu->name}\".";
            return;
        }

        $this->cart[$this->selectedMenuId] = $this->customizeQuantity;
        if (!empty($this->customizeNote)) {
            $this->itemNotes[$this->selectedMenuId] = $this->customizeNote;
        } else {
            unset($this->itemNotes[$this->selectedMenuId]);
        }

        $this->syncSession();
        $this->step = 'menu';
        $this->stockError = '';
    }

    public function goToCheckout(): void
    {
        $this->step = 'checkout';
    }

    public function goToMenu(): void
    {
        $this->step = 'menu';
    }

    public function incrementQty(int $menuId): void
    {
        $menu = Menu::find($menuId);
        $currentQty = $this->cart[$menuId] ?? 0;
        if ($menu && $currentQty < $menu->stock) {
            $this->cart[$menuId] = $currentQty + 1;
            $this->syncSession();
        }
    }

    public function decrementQty(int $menuId): void
    {
        $currentQty = $this->cart[$menuId] ?? 0;
        if ($currentQty > 1) {
            $this->cart[$menuId] = $currentQty - 1;
        } else {
            unset($this->cart[$menuId]);
            unset($this->itemNotes[$menuId]);
        }
        $this->syncSession();
    }

    public function removeFromCart(int $menuId): void
    {
        unset($this->cart[$menuId]);
        unset($this->itemNotes[$menuId]);
        $this->syncSession();
    }

    public function getTotalPriceProperty(): int
    {
        $total = 0;
        foreach ($this->cart as $menuId => $qty) {
            $menu = $this->menus->firstWhere('id', $menuId);
            if ($menu) {
                $total += $menu->price * $qty;
            }
        }
        return $total;
    }

    /**
     * Menghitung estimasi waktu penyelesaian (FR-005) berdasarkan jumlah
     * pesanan aktif penjual saat ini ditambah rata-rata durasi masak item di keranjang.
     */
    public function calculateEta(): int
    {
        $activeOrdersCount = Order::where('seller_id', $this->sellerId)
            ->whereIn('status', ['diterima', 'diproses'])
            ->count();

        $cookingTimes = [];
        foreach ($this->cart as $menuId => $qty) {
            $menu = $this->menus->firstWhere('id', $menuId);
            if ($menu) {
                $cookingTimes[] = $menu->cooking_time_minutes;
            }
        }

        $avgCookingTime = count($cookingTimes) > 0
            ? (int) round(array_sum($cookingTimes) / count($cookingTimes))
            : 10;

        // Setiap pesanan aktif di antrean menambah estimasi tunggu 3 menit.
        return ($activeOrdersCount * 3) + $avgCookingTime;
    }

    /**
     * Checkout: memvalidasi ulang stok seluruh item (mencegah race condition),
     * lalu menyimpan Order + OrderItem dengan status 'menunggu_pembayaran'.
     */
    public function checkout(): void
    {
        // Filter item dengan qty <= 0 (bisa terjadi saat stok habis dan updatedCart mengoreksi)
        $effectiveCart = array_filter($this->cart, fn ($qty) => (int) $qty > 0);

        if (empty($effectiveCart)) {
            $this->stockError = 'Keranjang masih kosong atau stok semua item habis.';
            return;
        }

        try {
            $order = DB::transaction(function () {
                foreach ($this->cart as $menuId => $qty) {
                    if ((int) $qty <= 0) {
                        continue; // skip item kosong yang tertinggal di cart
                    }
                    $menu = Menu::find($menuId);
                    if (! $menu || ! $menu->isAvailable($qty)) {
                        throw new \RuntimeException("Stok tidak mencukupi untuk menu \"{$menu?->name}\".");
                    }
                }

                $this->etaMinutes = $this->calculateEta();

                $order = Order::create([
                    'buyer_id' => Auth::id(),
                    'seller_id' => $this->sellerId,
                    'total_price' => $this->totalPrice,
                    'eta_minutes' => $this->etaMinutes,
                    'status' => 'menunggu_pembayaran',
                    'note' => $this->note,
                ]);

                foreach ($this->cart as $menuId => $qty) {
                    if ((int) $qty <= 0) {
                        continue;
                    }
                    $menu = Menu::find($menuId);

                    OrderItem::create([
                        'order_id'           => $order->id,
                        'menu_id'            => $menu->id,
                        'menu_name_snapshot' => $menu->name,
                        'price_snapshot'     => $menu->price,
                        'quantity'           => $qty,
                        'subtotal'           => $menu->price * $qty,
                        'notes'              => $this->itemNotes[$menu->id] ?? null,
                    ]);

                    // Stok dikurangi sementara, menunggu konfirmasi simulasi pembayaran.
                    $menu->decrement('stock', $qty);
                }

                \App\Services\AuditLogger::log('order.created', "Order #{$order->id} dibuat oleh User #".Auth::id());

                return $order;
            });

            $this->currentOrderId = $order->id;
            $this->showPaymentModal = true;
            $this->stockError = '';
        } catch (\RuntimeException $e) {
            $this->stockError = $e->getMessage();
        }
    }

    /**
     * Simulasi Pembayaran (FR-006/UC-07): mengubah status pesanan secara
     * internal pada server, tanpa request ke payment gateway eksternal mana pun.
     */
    public function simulatePayment(): void
    {
        $order = Order::find($this->currentOrderId);

        if (! $order || $order->status !== 'menunggu_pembayaran') {
            $this->stockError = 'Pesanan tidak ditemukan atau sudah berstatus final.';
            return;
        }

        DB::transaction(function () use ($order) {
            Payment::create([
                'order_id' => $order->id,
                'amount'   => $order->total_price,
                'method'   => 'dummy_payment',
                'status'   => 'lunas',
                'paid_at'  => now(),
            ]);

            $order->update(['status' => 'diterima']);
        });

        // Broadcast ke penjual agar dashboard menampilkan pesanan baru.
        broadcast(new OrderStatusUpdated($order))->toOthers();
        \App\Services\AuditLogger::log('order.paid', "Order #{$order->id} dibayar (simulasi) oleh User #".Auth::id());

        $this->showPaymentModal = false;
        $this->cart = [];
        $this->itemNotes = [];
        $this->note = null;
        $this->syncSession();
        $this->loadMenus();

        $this->dispatch('payment-completed', orderId: $order->id);
        $this->redirect(route('orders.track', ['order' => $order->id]), navigate: true);
    }

    public function cancelPaymentModal(): void
    {
        $this->showPaymentModal = false;
    }

    // FR-014 / UC-11: pembatalan hanya boleh sebelum penjual mulai memproses.
    public function cancelOrder(int $orderId): void
    {
        $order = Order::where('buyer_id', auth()->id())->findOrFail($orderId);

        if (! in_array($order->status, ['menunggu_pembayaran', 'diterima'])) {
            $this->stockError = 'Pesanan sudah diproses dan tidak dapat dibatalkan.';
            return;
        }

        foreach ($order->items as $item) {
            $item->menu->increment('stock', $item->quantity);
        }

        $order->update(['status' => 'dibatalkan']);

        // Broadcast agar dashboard penjual menampilkan pembatalan secara real-time.
        broadcast(new OrderStatusUpdated($order))->toOthers();
        \App\Services\AuditLogger::log('order.cancelled', "Order #{$order->id} dibatalkan mahasiswa");
    }

    public function render()
    {
        $filteredMenus = Menu::where('seller_id', $this->sellerId)
            ->where('is_active', true)
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->category, function ($query) {
                $query->where('category', $this->category);
            })
            ->orderBy('name')
            ->paginate(12);

        return view('livewire.canteen-order', [
            'filteredMenus' => $filteredMenus,
        ]);
    }
}