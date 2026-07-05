<?php

namespace App\Repositories\Admin;

use App\Models\Coupon;

class CouponRepository
{
    /**
     * Get all coupons with filtering, sorting, pagination, and trash state.
     */
    public function getAllPaginated(array $filters = [], int $perPage = 10)
    {
        $query = Coupon::query();

        // Search code
        if (!empty($filters['search'])) {
            $query->where('code', 'like', '%' . $filters['search'] . '%');
        }

        // Status
        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', (bool)$filters['status']);
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
     * Find coupon by ID (including trashed if requested).
     */
    public function find(int $id, bool $withTrashed = false): ?Coupon
    {
        return $withTrashed 
            ? Coupon::withTrashed()->find($id) 
            : Coupon::find($id);
    }

    public function findByCode(string $code): ?Coupon
    {
        return Coupon::where('code', $code)->first();
    }

    public function create(array $data): Coupon
    {
        return Coupon::create($data);
    }

    public function update(Coupon $coupon, array $data): bool
    {
        return $coupon->update($data);
    }

    public function delete(Coupon $coupon): bool
    {
        return $coupon->delete();
    }
}
