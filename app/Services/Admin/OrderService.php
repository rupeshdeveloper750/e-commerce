<?php

namespace App\Services\Admin;

use App\Models\Order;
use App\Models\ActivityLog;
use App\Repositories\Admin\OrderRepository;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        protected OrderRepository $orderRepository
    ) {}

    public function updateStatus(Order $order, string $status, string $paymentStatus): bool
    {
        return DB::transaction(function () use ($order, $status, $paymentStatus) {
            $original = $order->only(['status', 'payment_status']);
            $updated = $this->orderRepository->updateStatus($order, $status, $paymentStatus);

            if ($updated) {
                ActivityLog::log('updated', Order::class, $order->id, [
                    'old' => $original,
                    'new' => $order->only(['status', 'payment_status']),
                ]);
            }

            return $updated;
        });
    }

    /**
     * Soft delete order
     */
    public function destroy(Order $order): bool
    {
        return DB::transaction(function () use ($order) {
            $deleted = $order->delete();

            if ($deleted) {
                ActivityLog::log('deleted', Order::class, $order->id, [
                    'order_number' => $order->order_number,
                ]);
            }

            return $deleted;
        });
    }

    /**
     * Restore soft deleted order
     */
    public function restore(Order $order): bool
    {
        return DB::transaction(function () use ($order) {
            $restored = $order->restore();

            if ($restored) {
                ActivityLog::log('restored', Order::class, $order->id, [
                    'order_number' => $order->order_number,
                ]);
            }

            return $restored;
        });
    }

    /**
     * Force delete order
     */
    public function forceDelete(Order $order): bool
    {
        return DB::transaction(function () use ($order) {
            $orderNum = $order->order_number;
            $orderId = $order->id;

            // Delete associated items
            $order->items()->delete();
            $deleted = $order->forceDelete();

            if ($deleted) {
                ActivityLog::log('force_deleted', Order::class, $orderId, [
                    'order_number' => $orderNum,
                ]);
            }

            return $deleted;
        });
    }

    /**
     * Bulk Soft Delete
     */
    public function bulkDelete(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $count = 0;
            foreach ($ids as $id) {
                $order = Order::find($id);
                if ($order && $this->destroy($order)) {
                    $count++;
                }
            }
            return $count;
        });
    }

    /**
     * Bulk Restore
     */
    public function bulkRestore(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $count = 0;
            foreach ($ids as $id) {
                $order = Order::onlyTrashed()->find($id);
                if ($order && $this->restore($order)) {
                    $count++;
                }
            }
            return $count;
        });
    }

    /**
     * Bulk Force Delete
     */
    public function bulkForceDelete(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $count = 0;
            foreach ($ids as $id) {
                $order = Order::withTrashed()->find($id);
                if ($order && $this->forceDelete($order)) {
                    $count++;
                }
            }
            return $count;
        });
    }

    /**
     * Bulk update order status / payment status
     */
    public function bulkUpdateStatus(array $ids, string $status, string $paymentStatus): int
    {
        return DB::transaction(function () use ($ids, $status, $paymentStatus) {
            $count = 0;
            foreach ($ids as $id) {
                $order = Order::find($id);
                if ($order && $this->updateStatus($order, $status, $paymentStatus)) {
                    $count++;
                }
            }
            return $count;
        });
    }

    /**
     * Export Orders to CSV
     */
    public function exportCsv(): string
    {
        $orders = Order::with('user')->withTrashed()->get();
        $handle = fopen('php://temp', 'r+');
        
        // CSV headers
        fputcsv($handle, ['ID', 'Order Number', 'Customer Name', 'Customer Email', 'Subtotal', 'Tax', 'Discount', 'Total', 'Status', 'Payment Status', 'Created At', 'Deleted At']);

        foreach ($orders as $order) {
            fputcsv($handle, [
                $order->id,
                $order->order_number,
                $order->user ? $order->user->name : 'Guest',
                $order->user ? $order->user->email : '',
                $order->subtotal,
                $order->tax,
                $order->discount,
                $order->total,
                $order->status,
                $order->payment_status,
                $order->created_at ? $order->created_at->toDateTimeString() : '',
                $order->deleted_at ? $order->deleted_at->toDateTimeString() : '',
            ]);
        }

        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        ActivityLog::log('exported', Order::class, null, ['format' => 'CSV']);

        return $csvContent;
    }
}
