<?php

namespace App\Repositories\Admin;

use App\Models\Category;

class CategoryRepository
{
    /**
     * Get all categories with filtering, sorting, pagination, and trash state.
     */
    public function getAllPaginated(array $filters = [], int $perPage = 10)
    {
        $query = Category::query();

        // Search filter
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('slug', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        // Status filter
        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', (bool)$filters['status']);
        }

        // Parent filter
        if (isset($filters['parent_id'])) {
            if ($filters['parent_id'] === 'null') {
                $query->whereNull('parent_id');
            } elseif ($filters['parent_id'] === 'not_null') {
                $query->whereNotNull('parent_id');
            } else {
                $query->where('parent_id', $filters['parent_id']);
            }
        }

        // Trashed filter
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
     * Get parent categories
     */
    public function getParentCategories()
    {
        return Category::whereNull('parent_id')
            ->where('status', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * Find category (including trashed ones if requested)
     */
    public function find(int $id, bool $withTrashed = false): ?Category
    {
        return $withTrashed 
            ? Category::withTrashed()->find($id) 
            : Category::find($id);
    }

    /**
     * Store category
     */
    public function create(array $data): Category
    {
        return Category::create($data);
    }

    /**
     * Update category
     */
    public function update(Category $category, array $data): bool
    {
        return $category->update($data);
    }

    /**
     * Delete category (soft delete)
     */
    public function delete(Category $category): bool
    {
        return $category->delete();
    }
}