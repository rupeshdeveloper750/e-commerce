<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items'])
            ->whereNotNull('payment_status');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total'),
            'paid'          => Order::where('payment_status', 'paid')->count(),
            'pending'       => Order::where('payment_status', 'pending')->count(),
            'failed'        => Order::where('payment_status', 'failed')->count(),
        ];

        return view('admin.transaction.index', compact('transactions', 'stats'));
    }
}
