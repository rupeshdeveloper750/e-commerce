<?php

namespace App\Repositories\Admin;

use App\Models\Order;

class OrderRepository
{
    /**
     * Get all orders with advanced filtering, sorting, pagination, and trash state.
     */
    public function getAllPaginated(array $filters = [], int $perPage = 10)
    {
        $query = Order::with('user');

        // Search by order_number or customer name
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', '%' . $search . '%')
                         ->orWhere('email', 'like', '%' . $search . '%');
                  });
            });
        }

        // Status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Payment status
        if (!empty($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        // Trashed
        if (isset($filters['trashed']) && $filters['trashed'] === 'only') {
            $query->onlyTrashed();
        } elseif (isset($filters['trashed']) && $filters['trashed'] === 'with') {
            $query->withTrashed();
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Find order by ID (including trashed if requested).
     */
    public function find(int $id, bool $withTrashed = false): ?Order
    {
        return $withTrashed 
            ? Order::withTrashed()->with(['user', 'items.product'])->find($id) 
            : Order::with(['user', 'items.product'])->find($id);
    }

    public function updateStatus(Order $order, string $status, string $paymentStatus): bool
    {
        return $order->update([
            'status' => $status,
            'payment_status' => $paymentStatus
        ]);
    }
}
