<?php

namespace App\Services\Admin;

use App\Models\Brand;
use App\Models\ActivityLog;
use App\Repositories\Admin\BrandRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandService
{
    public function __construct(
        protected BrandRepository $brandRepository
    ) {}

    public function store(array $data): Brand
    {
        return DB::transaction(function () use ($data) {
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }

            if (!empty($data['image'])) {
                $data['image'] = $data['image']->store('brands', 'public');
            }

            $brand = $this->brandRepository->create($data);

            ActivityLog::log('created', Brand::class, $brand->id, [
                'name' => $brand->name,
                'slug' => $brand->slug,
            ]);

            return $brand;
        });
    }

    public function update(Brand $brand, array $data): bool
    {
        return DB::transaction(function () use ($brand, $data) {
            $original = $brand->only(['name', 'slug', 'status', 'is_featured']);

            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }

            if (!empty($data['image'])) {
                if ($brand->image) {
                    Storage::disk('public')->delete($brand->image);
                }
                $data['image'] = $data['image']->store('brands', 'public');
            }

            // Force bool casting for checkbox inputs if missing from request
            $data['is_featured'] = isset($data['is_featured']) ? (bool)$data['is_featured'] : false;

            $updated = $this->brandRepository->update($brand, $data);

            if ($updated) {
                ActivityLog::log('updated', Brand::class, $brand->id, [
                    'old' => $original,
                    'new' => $brand->only(['name', 'slug', 'status', 'is_featured']),
                ]);
            }

            return $updated;
        });
    }

    /**
     * Soft delete brand
     */
    public function destroy(Brand $brand): bool
    {
        return DB::transaction(function () use ($brand) {
            $deleted = $this->brandRepository->delete($brand);

            if ($deleted) {
                ActivityLog::log('deleted', Brand::class, $brand->id, [
                    'name' => $brand->name,
                ]);
            }

            return $deleted;
        });
    }

    /**
     * Restore soft-deleted brand
     */
    public function restore(Brand $brand): bool
    {
        return DB::transaction(function () use ($brand) {
            $restored = $brand->restore();

            if ($restored) {
                ActivityLog::log('restored', Brand::class, $brand->id, [
                    'name' => $brand->name,
                ]);
            }

            return $restored;
        });
    }

    /**
     * Force delete brand (permanently delete from database and storage)
     */
    public function forceDelete(Brand $brand): bool
    {
        return DB::transaction(function () use ($brand) {
            if ($brand->image) {
                Storage::disk('public')->delete($brand->image);
            }

            $brandName = $brand->name;
            $brandId = $brand->id;
            $deleted = $brand->forceDelete();

            if ($deleted) {
                ActivityLog::log('force_deleted', Brand::class, $brandId, [
                    'name' => $brandName,
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
                $brand = Brand::find($id);
                if ($brand && $this->destroy($brand)) {
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
                $brand = Brand::onlyTrashed()->find($id);
                if ($brand && $this->restore($brand)) {
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
                $brand = Brand::withTrashed()->find($id);
                if ($brand && $this->forceDelete($brand)) {
                    $count++;
                }
            }
            return $count;
        });
    }

    /**
     * Export Brands to CSV
     */
    public function exportCsv(): string
    {
        $brands = Brand::withTrashed()->get();
        $handle = fopen('php://temp', 'r+');
        
        // Write CSV headers
        fputcsv($handle, ['ID', 'Name', 'Slug', 'Description', 'Status', 'Is Featured', 'Deleted At']);

        foreach ($brands as $brand) {
            fputcsv($handle, [
                $brand->id,
                $brand->name,
                $brand->slug,
                $brand->description,
                $brand->status ? 'Active' : 'Inactive',
                $brand->is_featured ? 'Yes' : 'No',
                $brand->deleted_at ? $brand->deleted_at->toDateTimeString() : '',
            ]);
        }

        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        ActivityLog::log('exported', Brand::class, null, ['format' => 'CSV']);

        return $csvContent;
    }

    /**
     * Import Brands from CSV
     */
    public function importCsv(string $filePath): int
    {
        if (!file_exists($filePath)) {
            return 0;
        }

        $count = 0;
        if (($handle = fopen($filePath, 'r')) !== false) {
            fgetcsv($handle); // Read headers
            
            DB::transaction(function () use ($handle, &$count) {
                while (($row = fgetcsv($handle)) !== false) {
                    if (count($row) < 3) continue;

                    $name = $row[1] ?? '';
                    $slug = $row[2] ?? Str::slug($name);
                    $description = $row[3] ?? '';
                    $status = isset($row[4]) && strtolower($row[4]) === 'active';
                    $is_featured = isset($row[5]) && (strtolower($row[5]) === 'yes' || strtolower($row[5]) === 'true');

                    Brand::updateOrCreate(
                        ['slug' => $slug],
                        [
                            'name' => $name,
                            'description' => $description,
                            'status' => $status,
                            'is_featured' => $is_featured,
                        ]
                    );
                    $count++;
                }
            });
            
            fclose($handle);
        }

        ActivityLog::log('imported', Brand::class, null, ['count' => $count]);

        return $count;
    }
}
