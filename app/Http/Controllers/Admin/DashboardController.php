<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Define periods (30 days)
        $today = Carbon::today();
        $thirtyDaysAgo = Carbon::today()->subDays(30);
        $sixtyDaysAgo = Carbon::today()->subDays(60);

        // 1. Revenue Stats
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        
        $currentRevenue = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->sum('total');
            
        $prevRevenue = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', $sixtyDaysAgo)
            ->where('created_at', '<', $thirtyDaysAgo)
            ->sum('total');
            
        $revenueGrowth = $this->calculateGrowth($currentRevenue, $prevRevenue);

        // 2. Orders Stats
        $totalOrders = Order::count();
        
        $currentOrders = Order::where('created_at', '>=', $thirtyDaysAgo)->count();
        $prevOrders = Order::where('created_at', '>=', $sixtyDaysAgo)
            ->where('created_at', '<', $thirtyDaysAgo)
            ->count();
            
        $ordersGrowth = $this->calculateGrowth($currentOrders, $prevOrders);

        // 3. Products Stats
        $totalProducts = Product::count();
        $prevProducts = Product::where('created_at', '<', $thirtyDaysAgo)->count();
        $productsGrowth = $this->calculateGrowth($totalProducts - $prevProducts, $prevProducts);

        // 4. Customers Stats
        $totalCustomers = User::count();
        
        $currentCustomers = User::where('created_at', '>=', $thirtyDaysAgo)->count();
        $prevCustomers = User::where('created_at', '>=', $sixtyDaysAgo)
            ->where('created_at', '<', $thirtyDaysAgo)
            ->count();
            
        $customersGrowth = $this->calculateGrowth($currentCustomers, $prevCustomers);

        // 5. Recent Orders (Latest 5 orders)
        $recentOrders = Order::with('user')->latest()->limit(5)->get();

        // 6. Top Products (Best selling by quantity)
        $topProductsRaw = OrderItem::select('product_id', 'product_name', DB::raw('SUM(quantity) as sales_count'))
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('sales_count')
            ->limit(5)
            ->get();

        // Map categories and prices
        $topProducts = [];
        $maxSales = $topProductsRaw->max('sales_count') ?: 1;
        
        foreach ($topProductsRaw as $tp) {
            $productModel = Product::with('category', 'featuredImage')->find($tp->product_id);
            $topProducts[] = [
                'name' => $tp->product_name,
                'category' => $productModel && $productModel->category ? $productModel->category->name : 'N/A',
                'price' => '₹' . number_format($productModel ? ($productModel->sale_price ?? $productModel->price) : 0, 2),
                'sales' => $tp->sales_count,
                'image_path' => $productModel && $productModel->featuredImage ? $productModel->featuredImage->image_path : null,
                'sales_percentage' => round(($tp->sales_count / $maxSales) * 100)
            ];
        }

        // 7. Latest Customers
        $latestCustomers = User::latest()->limit(5)->get();

        // 8. Sales Analytics (Last 15 days)
        $analyticsDays = 15;
        $chartLabels = [];
        $chartData = [];
        
        for ($i = $analyticsDays - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateStr = $date->format('d M');
            $chartLabels[] = $dateStr;
            
            $dayRevenue = Order::where('payment_status', 'paid')
                ->whereDate('created_at', $date)
                ->sum('total');
                
            $chartData[] = round($dayRevenue, 2);
        }

        return view('admin.dashboard.dashboard', compact(
            'totalRevenue', 'revenueGrowth',
            'totalOrders', 'ordersGrowth',
            'totalProducts', 'productsGrowth',
            'totalCustomers', 'customersGrowth',
            'recentOrders', 'topProducts', 'latestCustomers',
            'chartLabels', 'chartData'
        ));
    }

    private function calculateGrowth($current, $previous)
    {
        if ($previous <= 0) {
            return $current > 0 ? '+100%' : '+0%';
        }
        
        $growth = (($current - $previous) / $previous) * 100;
        $sign = $growth >= 0 ? '+' : '';
        return $sign . round($growth, 1) . '%';
    }
}
