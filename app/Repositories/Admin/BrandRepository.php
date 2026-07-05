<?php

namespace App\Repositories\Admin;

use App\Models\Brand;

class BrandRepository
{
    /**
     * Get all brands with advanced filtering, sorting, and trash state.
     */
    public function getAllPaginated(array $filters = [], int $perPage = 10)
    {
        $query = Brand::query();

        // Search
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('slug', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        // Status
        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', (bool)$filters['status']);
        }

        // Featured
        if (isset($filters['is_featured']) && $filters['is_featured'] !== '') {
            $query->where('is_featured', (bool)$filters['is_featured']);
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
     * Get active brands.
     */
    public function getActiveBrands()
    {
        return Brand::where('status', true)->orderBy('name')->get();
    }

    /**
     * Find brand by ID (including trashed if requested).
     */
    public function find(int $id, bool $withTrashed = false): ?Brand
    {
        return $withTrashed 
            ? Brand::withTrashed()->find($id) 
            : Brand::find($id);
    }

    /**
     * Create new brand.
     */
    public function create(array $data): Brand
    {
        return Brand::create($data);
    }

    /**
     * Update brand.
     */
    public function update(Brand $brand, array $data): bool
    {
        return $brand->update($data);
    }

    /**
     * Delete brand.
     */
    public function delete(Brand $brand): bool
    {
        return $brand->delete();
    }
}
