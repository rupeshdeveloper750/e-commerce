<?php

namespace App\Services\Admin;

use App\Models\Category;
use App\Models\ActivityLog;
use App\Repositories\Admin\CategoryRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryService
{
    public function __construct(
        protected CategoryRepository $categoryRepository
    ) {
    }

    public function store(array $data): Category
    {
        return DB::transaction(function () use ($data) {
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }

            if (!empty($data['image'])) {
                $data['image'] = $data['image']->store('categories', 'public');
            }

            $category = $this->categoryRepository->create($data);

            ActivityLog::log('created', Category::class, $category->id, [
                'name' => $category->name,
                'slug' => $category->slug,
            ]);

            return $category;
        });
    }

    public function update(Category $category, array $data): bool
    {
        return DB::transaction(function () use ($category, $data) {
            $original = $category->only(['name', 'slug', 'parent_id', 'status']);

            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }

            if (!empty($data['image'])) {
                if ($category->image) {
                    Storage::disk('public')->delete($category->image);
                }
                $data['image'] = $data['image']->store('categories', 'public');
            }

            $updated = $this->categoryRepository->update($category, $data);

            if ($updated) {
                ActivityLog::log('updated', Category::class, $category->id, [
                    'old' => $original,
                    'new' => $category->only(['name', 'slug', 'parent_id', 'status']),
                ]);
            }

            return $updated;
        });
    }

    /**
     * Soft delete category
     */
    public function destroy(Category $category): bool
    {
        return DB::transaction(function () use ($category) {
            $deleted = $this->categoryRepository->delete($category);

            if ($deleted) {
                ActivityLog::log('deleted', Category::class, $category->id, [
                    'name' => $category->name,
                ]);
            }

            return $deleted;
        });
    }

    /**
     * Restore soft deleted category
     */
    public function restore(Category $category): bool
    {
        return DB::transaction(function () use ($category) {
            $restored = $category->restore();

            if ($restored) {
                ActivityLog::log('restored', Category::class, $category->id, [
                    'name' => $category->name,
                ]);
            }

            return $restored;
        });
    }

    /**
     * Force delete category (permanently delete record & physical image)
     */
    public function forceDelete(Category $category): bool
    {
        return DB::transaction(function () use ($category) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $categoryName = $category->name;
            $categoryId = $category->id;
            $deleted = $category->forceDelete();

            if ($deleted) {
                ActivityLog::log('force_deleted', Category::class, $categoryId, [
                    'name' => $categoryName,
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
                $category = Category::find($id);
                if ($category && $this->destroy($category)) {
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
                $category = Category::onlyTrashed()->find($id);
                if ($category && $this->restore($category)) {
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
                $category = Category::withTrashed()->find($id);
                if ($category && $this->forceDelete($category)) {
                    $count++;
                }
            }
            return $count;
        });
    }

    /**
     * Export Categories to CSV
     */
    public function exportCsv(): string
    {
        $categories = Category::withTrashed()->get();
        $handle = fopen('php://temp', 'r+');
        
        // Put CSV headers
        fputcsv($handle, ['ID', 'Name', 'Slug', 'Parent ID', 'Description', 'Status', 'Deleted At']);

        foreach ($categories as $cat) {
            fputcsv($handle, [
                $cat->id,
                $cat->name,
                $cat->slug,
                $cat->parent_id,
                $cat->description,
                $cat->status ? 'Active' : 'Inactive',
                $cat->deleted_at ? $cat->deleted_at->toDateTimeString() : '',
            ]);
        }

        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        ActivityLog::log('exported', Category::class, null, ['format' => 'CSV']);

        return $csvContent;
    }

    /**
     * Import Categories from CSV
     */
    public function importCsv(string $filePath): int
    {
        if (!file_exists($filePath)) {
            return 0;
        }

        $count = 0;
        if (($handle = fopen($filePath, 'r')) !== false) {
            $headers = fgetcsv($handle); // Read headers
            
            DB::transaction(function () use ($handle, &$count) {
                while (($row = fgetcsv($handle)) !== false) {
                    if (count($row) < 3) continue;

                    $name = $row[1] ?? '';
                    $slug = $row[2] ?? Str::slug($name);
                    $parent_id = (!empty($row[3]) && is_numeric($row[3])) ? (int)$row[3] : null;
                    $description = $row[4] ?? '';
                    $status = isset($row[5]) && strtolower($row[5]) === 'active';

                    Category::updateOrCreate(
                        ['slug' => $slug],
                        [
                            'name' => $name,
                            'parent_id' => $parent_id,
                            'description' => $description,
                            'status' => $status,
                        ]
                    );
                    $count++;
                }
            });
            
            fclose($handle);
        }

        ActivityLog::log('imported', Category::class, null, ['count' => $count]);

        return $count;
    }
}