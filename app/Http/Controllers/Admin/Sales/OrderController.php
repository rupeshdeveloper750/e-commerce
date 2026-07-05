<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Repositories\Admin\OrderRepository;
use App\Services\Admin\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

class OrderController extends Controller
{
    public function __construct(
        protected OrderRepository $orderRepository,
        protected OrderService $orderService
    ) {}

    public function index(Request $request)
    {
        Gate::authorize('manage-orders');

        $filters = $request->only(['search', 'status', 'payment_status', 'trashed', 'sort_by', 'sort_order']);
        $orders = $this->orderRepository->getAllPaginated($filters, 10);

        return view('admin.order.index', compact('orders'));
    }

    public function show($id)
    {
        Gate::authorize('manage-orders');

        $order = $this->orderRepository->find($id, true);

        return view('admin.order.show', compact('order'));
    }

    public function update(Request $request, $id)
    {
        Gate::authorize('manage-orders');

        $request->validate([
            'status' => ['required', 'string', 'in:pending,processing,shipped,delivered,cancelled'],
            'payment_status' => ['required', 'string', 'in:pending,paid,failed'],
        ]);

        $order = Order::withTrashed()->findOrFail($id);
        $this->orderService->updateStatus($order, $request->status, $request->payment_status);

        return redirect()
            ->route('admin.orders.show', $order->id)
            ->with('success', 'Order status updated successfully.');
    }

    public function restore($id)
    {
        Gate::authorize('manage-orders');

        $order = Order::onlyTrashed()->findOrFail($id);
        $this->orderService->restore($order);

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Order restored successfully.');
    }

    public function forceDelete($id)
    {
        Gate::authorize('manage-orders');

        $order = Order::withTrashed()->findOrFail($id);
        $this->orderService->forceDelete($order);

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Order permanently deleted.');
    }

    public function bulkAction(Request $request)
    {
        Gate::authorize('manage-orders');

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'numeric',
            'action' => 'required|string|in:delete,restore,force_delete,mark_processing,mark_shipped,mark_delivered,mark_paid',
        ]);

        $ids = $request->ids;
        $action = $request->action;
        $count = 0;

        if ($action === 'delete') {
            $count = $this->orderService->bulkDelete($ids);
            $msg = "{$count} orders soft-deleted successfully.";
        } elseif ($action === 'restore') {
            $count = $this->orderService->bulkRestore($ids);
            $msg = "{$count} orders restored successfully.";
        } elseif ($action === 'force_delete') {
            $count = $this->orderService->bulkForceDelete($ids);
            $msg = "{$count} orders permanently deleted.";
        } elseif ($action === 'mark_processing') {
            $count = $this->orderService->bulkUpdateStatus($ids, 'processing', 'pending');
            $msg = "{$count} orders marked as processing.";
        } elseif ($action === 'mark_shipped') {
            $count = $this->orderService->bulkUpdateStatus($ids, 'shipped', 'pending');
            $msg = "{$count} orders marked as shipped.";
        } elseif ($action === 'mark_delivered') {
            $count = $this->orderService->bulkUpdateStatus($ids, 'delivered', 'paid');
            $msg = "{$count} orders marked as delivered and paid.";
        } elseif ($action === 'mark_paid') {
            // Bulk update payment status to paid without changing fulfillment status
            $count = 0;
            foreach ($ids as $id) {
                $order = Order::find($id);
                if ($order && $this->orderService->updateStatus($order, $order->status, 'paid')) {
                    $count++;
                }
            }
            $msg = "{$count} orders marked as paid.";
        }

        return redirect()
            ->route('admin.orders.index')
            ->with('success', $msg);
    }

    public function export()
    {
        Gate::authorize('manage-orders');

        $csv = $this->orderService->exportCsv();

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="orders-export-' . now()->format('Y-m-d') . '.csv"',
        ]);
    }
}
