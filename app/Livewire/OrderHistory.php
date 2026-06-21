<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderHistory extends Component
{
    use WithPagination;

    public function render()
    {
        $orders = Order::with('items')
            ->where('buyer_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('livewire.order-history', ['orders' => $orders]);
    }
}