<?php

namespace App\Livewire;

use App\Livewire\Concerns\AuthorizesRole;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SalesReport extends Component
{
    use AuthorizesRole;

    public string $period = 'harian'; // harian|mingguan

    public function mount(): void
    {
        $this->authorizeRole('penjual');
        $this->redirect(route('seller.dashboard', ['tab' => 'reports']), navigate: true);
    }

    public function getSummaryProperty(): array
    {
        $start = $this->period === 'mingguan' ? now()->startOfWeek() : now()->startOfDay();

        $orders = Order::where('seller_id', Auth::id())
            ->where('status', 'selesai')
            ->where('created_at', '>=', $start)
            ->get();

        $bestSeller = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.seller_id', Auth::id())
            ->where('orders.status', 'selesai')
            ->where('orders.created_at', '>=', $start)
            ->select('order_items.menu_name_snapshot', DB::raw('SUM(order_items.quantity) as total_qty'))
            ->groupBy('order_items.menu_name_snapshot')
            ->orderByDesc('total_qty')
            ->first();

        return [
            'total_revenue' => $orders->sum('total_price'),
            'total_orders' => $orders->count(),
            'best_seller' => $bestSeller?->menu_name_snapshot ?? '-',
        ];
    }

    public function render()
    {
        return view('livewire.sales-report');
    }
}