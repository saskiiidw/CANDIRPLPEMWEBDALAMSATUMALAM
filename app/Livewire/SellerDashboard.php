<?php

namespace App\Livewire;

use App\Events\OrderStatusUpdated;
use App\Livewire\Concerns\AuthorizesRole;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SellerDashboard extends Component
{
    use AuthorizesRole;

    public $orders;

    public function mount(): void
    {
        $this->authorizeRole('penjual');
        $this->loadOrders();
    }

    // Listener dinamis karena nama channel mengandung ID penjual yang login.
    public function getListeners(): array
    {
        return [
            'echo-private:seller.'.Auth::id().',order.status.updated' => 'loadOrders',
        ];
    }

    public function loadOrders(): void
    {
        $this->orders = Order::with('items', 'buyer')
            ->where('seller_id', Auth::id())
            ->whereIn('status', ['diterima', 'diproses', 'siap_diambil'])
            ->latest()
            ->get();
    }

    public function process(int $orderId): void
    {
        $order = Order::where('seller_id', Auth::id())->findOrFail($orderId);
        if ($order->status !== 'diterima') return;

        $order->update(['status' => 'diproses']);
        broadcast(new OrderStatusUpdated($order))->toOthers();
        \App\Services\AuditLogger::log('order.status.changed', "Order #{$order->id} -> diproses");
        $this->loadOrders();
    }

    public function markReady(int $orderId): void
    {
        $order = Order::where('seller_id', Auth::id())->findOrFail($orderId);
        if ($order->status !== 'diproses') return;

        $order->update(['status' => 'siap_diambil']);
        broadcast(new OrderStatusUpdated($order))->toOthers();
        \App\Services\AuditLogger::log('order.status.changed', "Order #{$order->id} -> siap_diambil");
        $this->loadOrders();
    }

    public function reject(int $orderId): void
    {
        $order = Order::where('seller_id', Auth::id())->findOrFail($orderId);
        if (! in_array($order->status, ['diterima', 'diproses'])) return;

        foreach ($order->items as $item) {
            $item->menu->increment('stock', $item->quantity);
        }

        $order->update(['status' => 'ditolak']);
        broadcast(new OrderStatusUpdated($order))->toOthers();
        \App\Services\AuditLogger::log('order.status.changed', "Order #{$order->id} -> ditolak");
        $this->loadOrders();
    }

    public function render()
    {
        return view('livewire.seller-dashboard');
    }
}