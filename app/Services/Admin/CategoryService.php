<?php

namespace App\Services\Admin;

use App\Models\Category;
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

            return $this->categoryRepository->create($data);
        });
    }

    public function update(Category $category, array $data): bool
    {
        return DB::transaction(function () use ($category, $data) {

            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }

            if (!empty($data['image'])) {

                if ($category->image) {
                    Storage::disk('public')->delete($category->image);
                }

                $data['image'] = $data['image']->store('categories', 'public');
            }

            return $this->categoryRepository->update($category, $data);
        });
    }

    public function destroy(Category $category): bool
    {
        return DB::transaction(function () use ($category) {

            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            return $this->categoryRepository->delete($category);
        });
    }
}