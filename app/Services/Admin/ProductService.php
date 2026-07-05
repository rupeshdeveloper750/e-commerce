<?php

namespace App\Services\Admin;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ActivityLog;
use App\Repositories\Admin\ProductRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    public function __construct(
        protected ProductRepository $productRepository
    ) {}

    public function store(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);
            }
            if (empty($data['sku'])) {
                $data['sku'] = 'SM-' . strtoupper(Str::random(8));
            }

            // Extract images and variants
            $featuredImageFile = $data['featured_image'] ?? null;
            $galleryFiles = $data['gallery_images'] ?? [];
            $variantsData = $data['variants'] ?? [];

            // Unset relations from main table data
            unset($data['featured_image'], $data['gallery_images'], $data['variants']);

            $product = $this->productRepository->create($data);

            // Save featured image
            if ($featuredImageFile) {
                $path = $featuredImageFile->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_featured' => true,
                    'sort_order' => 0,
                ]);
            }

            // Save gallery images
            foreach ($galleryFiles as $index => $file) {
                $path = $file->store('products/gallery', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_featured' => false,
                    'sort_order' => $index + 1,
                ]);
            }

            // Save variants if present
            $this->syncVariants($product, $variantsData);

            ActivityLog::log('created', Product::class, $product->id, [
                'name' => $product->name,
                'sku' => $product->sku,
            ]);

            return $product;
        });
    }

    public function update(Product $product, array $data): bool
    {
        return DB::transaction(function () use ($product, $data) {
            $original = $product->only(['name', 'sku', 'price', 'quantity', 'status']);

            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);
            }

            // Extract images and variants
            $featuredImageFile = $data['featured_image'] ?? null;
            $galleryFiles = $data['gallery_images'] ?? [];
            $variantsData = $data['variants'] ?? [];

            // Unset from model data
            unset($data['featured_image'], $data['gallery_images'], $data['variants']);

            // Save featured image
            if ($featuredImageFile) {
                // Delete old featured image file & record
                $oldFeatured = $product->images()->where('is_featured', true)->first();
                if ($oldFeatured) {
                    Storage::disk('public')->delete($oldFeatured->image_path);
                    $oldFeatured->delete();
                }

                $path = $featuredImageFile->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_featured' => true,
                    'sort_order' => 0,
                ]);
            }

            // Save gallery images
            foreach ($galleryFiles as $index => $file) {
                $path = $file->store('products/gallery', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_featured' => false,
                    'sort_order' => $index + 1,
                ]);
            }

            // Save/Update variants
            $this->syncVariants($product, $variantsData);

            $updated = $this->productRepository->update($product, $data);

            if ($updated) {
                ActivityLog::log('updated', Product::class, $product->id, [
                    'old' => $original,
                    'new' => $product->only(['name', 'sku', 'price', 'quantity', 'status']),
                ]);
            }

            return $updated;
        });
    }

    /**
     * Soft delete product
     */
    public function destroy(Product $product): bool
    {
        return DB::transaction(function () use ($product) {
            $deleted = $this->productRepository->delete($product);

            if ($deleted) {
                // Soft delete its variants too
                $product->variants()->delete();

                ActivityLog::log('deleted', Product::class, $product->id, [
                    'name' => $product->name,
                ]);
            }

            return $deleted;
        });
    }

    /**
     * Restore soft deleted product
     */
    public function restore(Product $product): bool
    {
        return DB::transaction(function () use ($product) {
            $restored = $product->restore();

            if ($restored) {
                // Restore its variants too
                $product->variants()->restore();

                ActivityLog::log('restored', Product::class, $product->id, [
                    'name' => $product->name,
                ]);
            }

            return $restored;
        });
    }

    /**
     * Force delete product (permanently delete from database and storage)
     */
    public function forceDelete(Product $product): bool
    {
        return DB::transaction(function () use ($product) {
            // Physically delete images
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            // Delete variants and relationship records
            foreach ($product->variants as $variant) {
                $variant->attributeValues()->detach();
                $variant->forceDelete();
            }

            $productName = $product->name;
            $productId = $product->id;
            $deleted = $product->forceDelete();

            if ($deleted) {
                ActivityLog::log('force_deleted', Product::class, $productId, [
                    'name' => $productName,
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
                $product = Product::find($id);
                if ($product && $this->destroy($product)) {
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
                $product = Product::onlyTrashed()->find($id);
                if ($product && $this->restore($product)) {
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
                $product = Product::withTrashed()->find($id);
                if ($product && $this->forceDelete($product)) {
                    $count++;
                }
            }
            return $count;
        });
    }

    /**
     * Helper to sync variants
     */
    protected function syncVariants(Product $product, array $variantsData): void
    {
        if (empty($variantsData)) {
            return;
        }

        // Get current variant IDs to track removals
        $currentVariantIds = $product->variants->pluck('id')->toArray();
        $keptVariantIds = [];

        foreach ($variantsData as $variantItem) {
            $variantSku = $variantItem['sku'] ?? $product->sku . '-' . Str::random(3);
            
            $variant = ProductVariant::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'sku' => $variantSku,
                ],
                [
                    'price' => $variantItem['price'],
                    'sale_price' => $variantItem['sale_price'] ?? null,
                    'quantity' => $variantItem['quantity'] ?? 0,
                ]
            );

            $keptVariantIds[] = $variant->id;

            // Associate attributes pivot
            if (!empty($variantItem['attribute_values'])) {
                $variant->attributeValues()->sync($variantItem['attribute_values']);
            }
        }

        // Delete removed variants
        $removedIds = array_diff($currentVariantIds, $keptVariantIds);
        if (!empty($removedIds)) {
            ProductVariant::whereIn('id', $removedIds)->delete();
        }
    }

    /**
     * Export Products to CSV
     */
    public function exportCsv(): string
    {
        $products = Product::with(['category', 'brand'])->withTrashed()->get();
        $handle = fopen('php://temp', 'r+');
        
        // CSV headers
        fputcsv($handle, ['ID', 'Name', 'Slug', 'SKU', 'Category', 'Brand', 'Price', 'Sale Price', 'Quantity', 'Status', 'Featured', 'Deleted At']);

        foreach ($products as $prod) {
            fputcsv($handle, [
                $prod->id,
                $prod->name,
                $prod->slug,
                $prod->sku,
                $prod->category ? $prod->category->name : '',
                $prod->brand ? $prod->brand->name : '',
                $prod->price,
                $prod->sale_price,
                $prod->quantity,
                $prod->status ? 'Active' : 'Inactive',
                $prod->is_featured ? 'Yes' : 'No',
                $prod->deleted_at ? $prod->deleted_at->toDateTimeString() : '',
            ]);
        }

        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        ActivityLog::log('exported', Product::class, null, ['format' => 'CSV']);

        return $csvContent;
    }

    /**
     * Import Products from CSV
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
                    if (count($row) < 7) continue;

                    $name = $row[1] ?? '';
                    $slug = $row[2] ?? Str::slug($name);
                    $sku = $row[3] ?? 'SM-' . strtoupper(Str::random(8));
                    
                    // Category lookup
                    $categoryName = $row[4] ?? '';
                    $category = null;
                    if ($categoryName) {
                        $category = Category::firstOrCreate(
                            ['name' => $categoryName],
                            ['slug' => Str::slug($categoryName)]
                        );
                    }

                    // Brand lookup
                    $brandName = $row[5] ?? '';
                    $brand = null;
                    if ($brandName) {
                        $brand = Brand::firstOrCreate(
                            ['name' => $brandName],
                            ['slug' => Str::slug($brandName)]
                        );
                    }

                    $price = (float)($row[6] ?? 0.00);
                    $sale_price = (!empty($row[7])) ? (float)$row[7] : null;
                    $quantity = (int)($row[8] ?? 0);
                    $status = isset($row[9]) && strtolower($row[9]) === 'active';
                    $is_featured = isset($row[10]) && (strtolower($row[10]) === 'yes' || strtolower($row[10]) === 'true');

                    Product::updateOrCreate(
                        ['sku' => $sku],
                        [
                            'category_id' => $category ? $category->id : null,
                            'brand_id' => $brand ? $brand->id : null,
                            'name' => $name,
                            'slug' => $slug,
                            'price' => $price,
                            'sale_price' => $sale_price,
                            'quantity' => $quantity,
                            'status' => $status,
                            'is_featured' => $is_featured,
                        ]
                    );
                    $count++;
                }
            });
            
            fclose($handle);
        }

        ActivityLog::log('imported', Product::class, null, ['count' => $count]);

        return $count;
    }
}
