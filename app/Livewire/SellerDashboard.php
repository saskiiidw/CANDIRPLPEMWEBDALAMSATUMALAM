<?php

namespace App\Livewire;

use App\Events\OrderStatusUpdated;
use App\Livewire\Concerns\AuthorizesRole;
use App\Models\Order;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class SellerDashboard extends Component
{
    use AuthorizesRole, WithFileUploads;

    public string $activeTab = 'dashboard';
    public ?int $selectedOrderId = null;

    // Menu and Stock tab properties
    public string $searchQuery = '';
    public string $selectedCategory = 'all';
    public bool $showMenuModal = false;
    public ?int $editingMenuId = null;
    public string $menuName = '';
    public string $menuDescription = '';
    public string $menuCategory = 'makanan_berat';
    public int $menuPrice = 0;
    public int $menuStock = 0;
    public int $menuCookingTime = 10;

    // Profile tab properties
    public string $storeName = '';
    public string $sellerDescription = '';
    public string $sellerName = '';
    public string $sellerRole = 'Head Chef & Manager';
    public string $sellerPhone = '';
    public string $sellerLocation = 'Student Union Building, Floor 2';
    public bool $alertNewOrder = true;
    public bool $alertLowStock = true;

    public function mount(): void
    {
        $this->authorizeRole('penjual');
        
        // Initialize profile properties
        $user = Auth::user();
        $this->storeName = $user->store_name ?? '';
        $this->sellerDescription = $user->description ?? 'Serving fresh, locally sourced meals.';
        $this->sellerName = $user->name ?? '';
        $this->sellerPhone = $user->phone ?? '';

        // Check for tab query parameter
        $tab = request()->query('tab');
        if (in_array($tab, ['dashboard', 'menu_stock', 'reports', 'profile'])) {
            $this->activeTab = $tab;
        }
    }

    public function getListeners(): array
    {
        return [
            'echo-private:seller.'.Auth::id().',order.status.updated' => '$refresh',
        ];
    }

    public function toggleStoreStatus(): void
    {
        $user = Auth::user();
        $user->update(['is_active' => !$user->is_active]);
        \App\Services\AuditLogger::log('seller.status.toggled', "Store status toggled to: " . ($user->is_active ? 'Active' : 'Inactive'));
    }

    public function selectOrder(?int $orderId): void
    {
        $this->selectedOrderId = $orderId;
    }

    public function process(int $orderId): void
    {
        $order = Order::where('seller_id', Auth::id())->findOrFail($orderId);
        if ($order->status !== 'diterima') return;

        $order->update(['status' => 'diproses']);
        broadcast(new OrderStatusUpdated($order))->toOthers();
        \App\Services\AuditLogger::log('order.status.changed', "Order #{$order->id} -> diproses");
        
        session()->flash('message', "Order #{$order->id} has been accepted.");
    }

    public function markReady(int $orderId): void
    {
        $order = Order::where('seller_id', Auth::id())->findOrFail($orderId);
        if ($order->status !== 'diproses') return;

        $order->update(['status' => 'siap_diambil']);
        broadcast(new OrderStatusUpdated($order))->toOthers();
        \App\Services\AuditLogger::log('order.status.changed', "Order #{$order->id} -> siap_diambil");
        
        session()->flash('message', "Order #{$order->id} is ready for pick up.");
    }

    public function completeOrder(int $orderId): void
    {
        $order = Order::where('seller_id', Auth::id())->findOrFail($orderId);
        if ($order->status !== 'siap_diambil') return;

        $order->update(['status' => 'selesai']);
        broadcast(new OrderStatusUpdated($order))->toOthers();
        \App\Services\AuditLogger::log('order.status.changed', "Order #{$order->id} -> selesai");
        
        session()->flash('message', "Order #{$order->id} marked as completed.");
        $this->selectedOrderId = null;
    }

    public function reject(int $orderId): void
    {
        $order = Order::where('seller_id', Auth::id())->findOrFail($orderId);
        if (!in_array($order->status, ['diterima', 'diproses'])) return;

        foreach ($order->items as $item) {
            if ($item->menu) {
                $item->menu->increment('stock', $item->quantity);
            }
        }

        $order->update(['status' => 'ditolak']);
        broadcast(new OrderStatusUpdated($order))->toOthers();
        \App\Services\AuditLogger::log('order.status.changed', "Order #{$order->id} -> ditolak");
        
        session()->flash('message', "Order #{$order->id} has been rejected.");
        $this->selectedOrderId = null;
    }

    // Menu and stock methods
    public function adjustStock(int $menuId, int $change): void
    {
        $menu = Menu::where('seller_id', Auth::id())->findOrFail($menuId);
        $menu->update(['stock' => max(0, $menu->stock + $change)]);
    }

    public function restock(int $menuId): void
    {
        $menu = Menu::where('seller_id', Auth::id())->findOrFail($menuId);
        $menu->update(['stock' => 20]); // Restock batch default 20
    }

    public function openMenuModal(?int $menuId = null): void
    {
        $this->editingMenuId = $menuId;
        if ($menuId) {
            $menu = Menu::where('seller_id', Auth::id())->findOrFail($menuId);
            $this->menuName = $menu->name;
            $this->menuDescription = $menu->description ?? '';
            $this->menuCategory = $menu->category;
            $this->menuPrice = $menu->price;
            $this->menuStock = $menu->stock;
            $this->menuCookingTime = $menu->cooking_time_minutes;
        } else {
            $this->reset(['menuName', 'menuDescription', 'menuCategory', 'menuPrice', 'menuStock', 'menuCookingTime']);
            $this->menuCategory = 'makanan_berat';
            $this->menuCookingTime = 10;
        }
        $this->showMenuModal = true;
    }

    public function saveMenu(): void
    {
        $this->validate([
            'menuName' => 'required|string|max:255',
            'menuDescription' => 'nullable|string',
            'menuCategory' => 'required|in:makanan_berat,makanan_ringan,minuman',
            'menuPrice' => 'required|numeric|min:0',
            'menuStock' => 'required|integer|min:0',
            'menuCookingTime' => 'required|integer|min:1',
        ]);

        $data = [
            'seller_id' => Auth::id(),
            'name' => $this->menuName,
            'description' => $this->menuDescription,
            'category' => $this->menuCategory,
            'price' => $this->menuPrice,
            'stock' => $this->menuStock,
            'cooking_time_minutes' => $this->menuCookingTime,
            'is_active' => true,
        ];

        if ($this->editingMenuId) {
            $menu = Menu::where('seller_id', Auth::id())->findOrFail($this->editingMenuId);
            $menu->update($data);
            session()->flash('message', 'Menu item updated successfully.');
        } else {
            Menu::create($data);
            session()->flash('message', 'Menu item created successfully.');
        }

        $this->showMenuModal = false;
        $this->reset(['menuName', 'menuDescription', 'menuPrice', 'menuStock', 'menuCookingTime']);
    }

    public function deleteMenu(int $menuId): void
    {
        $menu = Menu::where('seller_id', Auth::id())->findOrFail($menuId);
        $menu->delete();
        session()->flash('message', 'Menu item deleted successfully.');
    }

    // Profile updates
    public function updateProfile(): void
    {
        $this->validate([
            'storeName' => 'required|string|max:255',
            'sellerDescription' => 'required|string|max:500',
            'sellerName' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $user->update([
            'store_name' => $this->storeName,
            'description' => $this->sellerDescription,
            'name' => $this->sellerName,
            'phone' => $this->sellerPhone,
        ]);

        session()->flash('message', 'Profile updated successfully.');
    }

    public function render()
    {
        $sellerId = Auth::id();

        // 1. Dashboard active orders
        $orders = Order::with('items', 'buyer')
            ->where('seller_id', $sellerId)
            ->whereIn('status', ['diterima', 'diproses', 'siap_diambil'])
            ->latest()
            ->get();

        $selectedOrder = $this->selectedOrderId 
            ? Order::with('items.menu', 'buyer')->where('seller_id', $sellerId)->find($this->selectedOrderId)
            : null;

        // 2. Menu and Stock filter
        $menus = Menu::where('seller_id', $sellerId)
            ->when($this->searchQuery, function($q) {
                $q->where('name', 'like', '%' . $this->searchQuery . '%');
            })
            ->when($this->selectedCategory !== 'all', function($q) {
                $q->where('category', $this->selectedCategory);
            })
            ->latest()
            ->get();

        // 3. Stats for dashboard/reports
        $stats = [
            'new_orders' => Order::where('seller_id', $sellerId)->where('status', 'diterima')->count(),
            'processing' => Order::where('seller_id', $sellerId)->where('status', 'diproses')->count(),
            'ready' => Order::where('seller_id', $sellerId)->where('status', 'siap_diambil')->count(),
            'completed' => Order::where('seller_id', $sellerId)->where('status', 'selesai')->count(),
            
            'today_revenue' => Order::where('seller_id', $sellerId)
                ->where('status', 'selesai')
                ->whereDate('created_at', today())
                ->sum('total_price'),

            'weekly_revenue' => Order::where('seller_id', $sellerId)
                ->where('status', 'selesai')
                ->where('created_at', '>=', now()->startOfWeek())
                ->sum('total_price'),
                
            'total_orders_today' => Order::where('seller_id', $sellerId)
                ->whereDate('created_at', today())
                ->count(),
        ];

        // 4. Reports top items
        $topItems = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.seller_id', $sellerId)
            ->where('orders.status', 'selesai')
            ->select('order_items.menu_name_snapshot', DB::raw('SUM(order_items.quantity) as total_qty'), DB::raw('SUM(order_items.subtotal) as total_subtotal'))
            ->groupBy('order_items.menu_name_snapshot')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return view('livewire.seller-dashboard', [
            'orders' => $orders,
            'selectedOrder' => $selectedOrder,
            'menus' => $menus,
            'stats' => $stats,
            'topItems' => $topItems,
        ])->layout('layouts.app');
    }
}