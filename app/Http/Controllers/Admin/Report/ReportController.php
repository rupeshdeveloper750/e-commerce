<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Review;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', '30'); // days

        $startDate = now()->subDays((int) $period);

        // Revenue Stats
        $totalRevenue   = Order::where('payment_status', 'paid')->where('created_at', '>=', $startDate)->sum('total');
        $prevRevenue    = Order::where('payment_status', 'paid')->where('created_at', '<', $startDate)->where('created_at', '>=', now()->subDays((int) $period * 2))->sum('total');
        $revenueGrowth  = $prevRevenue > 0 ? round((($totalRevenue - $prevRevenue) / $prevRevenue) * 100, 1) : 0;

        // Orders Stats
        $totalOrders    = Order::where('created_at', '>=', $startDate)->count();
        $prevOrders     = Order::where('created_at', '<', $startDate)->where('created_at', '>=', now()->subDays((int) $period * 2))->count();
        $ordersGrowth   = $prevOrders > 0 ? round((($totalOrders - $prevOrders) / $prevOrders) * 100, 1) : 0;

        // Customers Stats
        $newCustomers   = User::where('created_at', '>=', $startDate)->count();
        $totalCustomers = User::count();

        // Average Order Value
        $avgOrderValue  = Order::where('payment_status', 'paid')->where('created_at', '>=', $startDate)->avg('total') ?? 0;

        // Revenue by Day (last N days)
        $revenueChart = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', $startDate)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        // Top Selling Products
        $topProducts = OrderItem::select('product_id',
                DB::raw('SUM(quantity) as total_sold'),
                DB::raw('SUM(subtotal) as total_revenue')
            )
            ->with('product')
            ->whereHas('order', fn($q) => $q->where('payment_status', 'paid')->where('created_at', '>=', $startDate))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(8)
            ->get();

        // Orders by Status
        $ordersByStatus = Order::select('status', DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Payment Methods
        $paymentMethods = Order::select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as revenue'))
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', $startDate)
            ->groupBy('payment_method')
            ->get();

        return view('admin.report.index', compact(
            'totalRevenue', 'revenueGrowth',
            'totalOrders', 'ordersGrowth',
            'newCustomers', 'totalCustomers',
            'avgOrderValue',
            'revenueChart', 'topProducts',
            'ordersByStatus', 'paymentMethods',
            'period'
        ));
    }
}
