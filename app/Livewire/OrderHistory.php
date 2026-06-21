<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderHistory extends Component
{
    use WithPagination;

    public string $search = '';
    public ?string $startDate = null;
    public ?string $endDate = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'startDate' => ['except' => null],
        'endDate' => ['except' => null],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStartDate(): void
    {
        $this->resetPage();
    }

    public function updatingEndDate(): void
    {
        $this->resetPage();
    }

    public function reorder(int $orderId): void
    {
        $order = Order::with('items')->findOrFail($orderId);

        $cart = [];
        $itemNotes = [];

        foreach ($order->items as $item) {
            $cart[$item->menu_id] = $item->quantity;
            if ($item->notes) {
                $itemNotes[$item->menu_id] = $item->notes;
            }
        }

        session(['cart' => $cart]);
        session(['itemNotes' => $itemNotes]);

        $this->redirect(route('canteen.order', ['seller' => $order->seller_id]), navigate: true);
    }

    public function render()
    {
        $query = Order::with(['items', 'seller'])
            ->where('buyer_id', auth()->id())
            ->latest();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->whereHas('seller', function ($sq) {
                    $sq->where('store_name', 'like', '%' . $this->search . '%');
                })->orWhereHas('items', function ($iq) {
                    $iq->where('menu_name_snapshot', 'like', '%' . $this->search . '%');
                });
            });
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59']);
        }

        $orders = $query->paginate(10);

        return view('livewire.order-history', ['orders' => $orders]);
    }
}