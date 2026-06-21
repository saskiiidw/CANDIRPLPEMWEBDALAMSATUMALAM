<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class OrderStatusTracker extends Component
{
    public Order $order;
    public string $notificationMessage = '';

    public function mount(Order $order): void
    {
        if ($order->buyer_id !== auth()->id()) {
            abort(403);
        }
        $this->order = $order;
    }

    public function getListeners(): array
    {
        return [
            "echo-private:order.{$this->order->id},order.status.updated" => 'refreshStatus',
        ];
    }

    public function refreshStatus(): void
    {
        $this->order->refresh();

        $this->notificationMessage = match ($this->order->status) {
            'diproses' => 'Pesanan Anda sedang diproses penjual.',
            'siap_diambil' => 'Pesanan Anda sudah siap diambil di kantin '.$this->order->seller->store_name.'.',
            'selesai' => 'Pesanan Anda telah selesai.',
            'ditolak' => 'Maaf, pesanan Anda ditolak penjual.',
            default => '',
        };
    }

    // FR-015 / UC-14: konfirmasi penerimaan pesanan.
    public function confirmReceived(): void
    {
        if ($this->order->status !== 'siap_diambil') {
            return;
        }

        $this->order->update(['status' => 'selesai']);

        // Broadcast agar dashboard penjual juga merefleksikan status selesai.
        broadcast(new \App\Events\OrderStatusUpdated($this->order))->toOthers();
        \App\Services\AuditLogger::log('order.confirmed', "Order #{$this->order->id} dikonfirmasi diterima mahasiswa");
        $this->refreshStatus();
    }

    public function render()
    {
        return view('livewire.order-status-tracker');
    }
}