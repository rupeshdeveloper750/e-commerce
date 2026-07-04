<?php

namespace App\Repositories\Admin;

use App\Models\Category;

class CategoryRepository
{
    /**
     * Get all categories
     */
    public function getAllPaginated(int $perPage = 10)
    {
        return Category::latest()->paginate($perPage);
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
     * Find category
     */
    public function find(int $id): ?Category
    {
        return Category::find($id);
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
     * Delete category
     */
    public function delete(Category $category): bool
    {
        return $category->delete();
    }
}