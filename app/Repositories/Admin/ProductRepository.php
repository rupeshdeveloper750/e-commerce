<?php

namespace App\Repositories\Admin;

use App\Models\Product;

class ProductRepository
{
    /**
     * Get all products with advanced filtering, sorting, pagination, and trash state.
     */
    public function getAllPaginated(array $filters = [], int $perPage = 10)
    {
        $query = Product::with(['category', 'brand', 'featuredImage']);

        // Search
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('slug', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('sku', 'like', '%' . $filters['search'] . '%');
            });
        }

        // Category
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // Brand
        if (!empty($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }

        // Status
        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', (bool)$filters['status']);
        }

        // Featured
        if (isset($filters['is_featured']) && $filters['is_featured'] !== '') {
            $query->where('is_featured', (bool)$filters['is_featured']);
        }

        // Stock alerts (Low stock <= 5)
        if (isset($filters['stock_level']) && $filters['stock_level'] === 'low') {
            $query->where('quantity', '<=', 5);
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
     * Find product by ID (including trashed if requested).
     */
    public function find(int $id, bool $withTrashed = false): ?Product
    {
        return $withTrashed 
            ? Product::withTrashed()->with(['category', 'brand', 'images', 'variants.attributeValues.attribute'])->find($id) 
            : Product::with(['category', 'brand', 'images', 'variants.attributeValues.attribute'])->find($id);
    }

    /**
     * Create product.
     */
    public function create(array $data): Product
    {
        return Product::create($data);
    }

    /**
     * Update product.
     */
    public function update(Product $product, array $data): bool
    {
        return $product->update($data);
    }

    /**
     * Delete product.
     */
    public function delete(Product $product): bool
    {
        return $product->delete();
    }
}
