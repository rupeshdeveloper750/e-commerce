<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BrandFeature;

class BrandFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            [
                'title' => 'Italian Leather Craftsmanship',
                'description' => 'Made in Tuscany, using vegetable-tanned leathers and handcrafted metal hardware.',
                'icon' => 'sparkles',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Sustainable Philosophy',
                'description' => 'Designed with circularity in mind. Zero waste packaging and ethical labor standards.',
                'icon' => 'leaf',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => '5-Year Precision Warranty',
                'description' => 'We stand behind every hand-stitched hem and micro-movement watch cog for five years.',
                'icon' => 'shield-check',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Concierge Tailored Support',
                'description' => 'Direct premium assistance, package tailoring, and customized lookbook creation.',
                'icon' => 'headset',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($features as $feat) {
            BrandFeature::updateOrCreate(
                ['title' => $feat['title']],
                $feat
            );
        }
    }
}
