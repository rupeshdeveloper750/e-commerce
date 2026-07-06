<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Fashion' => [
                'Men Clothing',
                'Women Clothing',
                'Kids Wear',
                'Accessories'
            ],
            'Electronics' => [
                'Mobiles',
                'Laptops',
                'Tablets',
                'Headphones'
            ],
            'Footwear' => [
                'Sneakers',
                'Sports Shoes',
                'Formal Shoes',
                'Sandals'
            ],
            'Watches' => [
                'Analog',
                'Digital',
                'Smart Watches',
                'Luxury'
            ],
            'Bags' => [
                'Hand Bags',
                'Backpacks',
                'Travel Bags',
                'Laptop Bags'
            ]
        ];

        $sortOrder = 1;
        foreach ($categories as $parentName => $subCategories) {
            $parentSlug = Str::slug($parentName);
            
            // Create or update parent category
            $parent = Category::updateOrCreate(
                ['slug' => $parentSlug],
                [
                    'name' => $parentName,
                    'parent_id' => null,
                    'status' => true,
                    'sort_order' => $sortOrder++,
                    'description' => $parentName . ' Category',
                    'meta_title' => $parentName,
                    'meta_description' => 'Explore the best in ' . $parentName
                ]
            );

            $subSortOrder = 1;
            foreach ($subCategories as $subName) {
                $subSlug = Str::slug($subName);
                
                Category::updateOrCreate(
                    ['slug' => $subSlug],
                    [
                        'name' => $subName,
                        'parent_id' => $parent->id,
                        'status' => true,
                        'sort_order' => $subSortOrder++,
                        'description' => $subName . ' under ' . $parentName,
                        'meta_title' => $subName,
                        'meta_description' => 'Find the latest ' . $subName
                    ]
                );
            }
        }
    }
}
