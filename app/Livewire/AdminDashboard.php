<?php

namespace App\Livewire;

use App\Livewire\Concerns\AuthorizesRole;
use App\Models\User;
use App\Models\Order;
use App\Models\AuditLog;
use Livewire\Component;
use Carbon\Carbon;

class AdminDashboard extends Component
{
    use AuthorizesRole;

    public string $search = '';

    public function mount(): void
    {
        $this->authorizeRole('admin');
    }

    public function render()
    {
        // 1. Real-time stats — no fallbacks
        $ordersToday         = Order::whereDate('created_at', Carbon::today())->count();
        $totalStudents       = User::where('role', 'mahasiswa')->count();
        $completedToday      = Order::whereDate('created_at', Carbon::today())->where('status', 'selesai')->count();
        $totalSellers        = User::where('role', 'penjual')->count();
        $activeSellers       = User::where('role', 'penjual')->where('is_verified', true)->where('is_active', true)->count();
        $rejectedToday       = Order::whereDate('created_at', Carbon::today())->whereIn('status', ['ditolak', 'dibatalkan'])->count();
        $totalRevenue        = Order::whereDate('created_at', Carbon::today())->where('status', 'selesai')->sum('total_price');
        $pendingVerification = User::where('role', 'penjual')->where('is_verified', false)->count();

        // 2. Completion rate (avoid division by zero)
        $completionRate = $ordersToday > 0 ? round(($completedToday / $ordersToday) * 100) : 0;

        // 3. Yesterday comparison for trend indicator
        $ordersYesterday = Order::whereDate('created_at', Carbon::yesterday())->count();
        $ordersTrend     = $ordersYesterday > 0
            ? round((($ordersToday - $ordersYesterday) / $ordersYesterday) * 100, 1)
            : 0;

        // 4. Recent activity from real audit logs only
        $activities = AuditLog::with('user')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get()
            ->map(function ($log) {
                $title    = 'Activity Logged';
                $dotColor = 'bg-gray-400';

                if (str_contains($log->action, 'login'))          { $title = 'User Login';       $dotColor = 'bg-green-500'; }
                elseif (str_contains($log->action, 'verified'))   { $title = 'Seller Verified';  $dotColor = 'bg-[#E27226]'; }
                elseif (str_contains($log->action, 'rejected'))   { $title = 'Seller Rejected';  $dotColor = 'bg-red-500'; }
                elseif (str_contains($log->action, 'status'))     { $title = 'Status Updated';   $dotColor = 'bg-blue-500'; }
                elseif (str_contains($log->action, 'order'))      { $title = 'Order Event';      $dotColor = 'bg-amber-500'; }
                elseif (str_contains($log->action, 'registered')) { $title = 'New Registration'; $dotColor = 'bg-indigo-500'; }
                elseif (str_contains($log->action, 'profile'))    { $title = 'Profile Updated';  $dotColor = 'bg-teal-500'; }

                return [
                    'title'       => $title,
                    'description' => $log->description,
                    'time'        => $log->created_at->diffForHumans(),
                    'dotColor'    => $dotColor,
                    'user'        => $log->user?->name ?? 'System',
                ];
            })
            ->toArray();

        return view('livewire.admin-dashboard', [
            'ordersToday'         => $ordersToday,
            'totalStudents'       => $totalStudents,
            'completedToday'      => $completedToday,
            'totalSellers'        => $totalSellers,
            'activeSellers'       => $activeSellers,
            'rejectedToday'       => $rejectedToday,
            'totalRevenue'        => $totalRevenue,
            'completionRate'      => $completionRate,
            'ordersTrend'         => $ordersTrend,
            'pendingVerification' => $pendingVerification,
            'activities'          => $activities,
        ])->layout('layouts.admin');
    }
}
