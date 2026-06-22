<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SellerReportController extends Controller
{
    public function exportPdf(Request $request)
    {
        $sellerId = Auth::id();
        $user     = Auth::user();

        // Month/Year: default ke bulan berjalan
        $month = (int) $request->query('month', now()->month);
        $year  = (int) $request->query('year', now()->year);

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate   = Carbon::create($year, $month, 1)->endOfMonth();

        // Summary stats
        $totalRevenue = Order::where('seller_id', $sellerId)
            ->where('status', 'selesai')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_price');

        $totalOrders = Order::where('seller_id', $sellerId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $completedOrders = Order::where('seller_id', $sellerId)
            ->where('status', 'selesai')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $daysInMonth    = $startDate->daysInMonth;
        $avgDailyRevenue = $daysInMonth > 0 ? round($totalRevenue / $daysInMonth) : 0;

        $summary = [
            'total_revenue'     => $totalRevenue,
            'total_orders'      => $totalOrders,
            'completed_orders'  => $completedOrders,
            'avg_daily_revenue' => $avgDailyRevenue,
        ];

        // Top items
        $topItems = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.seller_id', $sellerId)
            ->where('orders.status', 'selesai')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'order_items.menu_name_snapshot',
                DB::raw('SUM(order_items.quantity) as total_qty'),
                DB::raw('SUM(order_items.subtotal) as total_subtotal')
            )
            ->groupBy('order_items.menu_name_snapshot')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        // Recent orders
        $orders = Order::with('buyer')
            ->where('seller_id', $sellerId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        $pdf = Pdf::loadView('pdf.monthly-report', [
            'storeName' => $user->store_name ?? $user->name,
            'month'     => $month,
            'year'      => $year,
            'summary'   => $summary,
            'topItems'  => $topItems,
            'orders'    => $orders,
        ])->setPaper('a4', 'portrait');

        $filename = 'laporan-bulanan-' . Carbon::create($year, $month)->format('Y-m') . '.pdf';

        return $pdf->download($filename);
    }
}
