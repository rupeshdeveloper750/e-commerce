<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'brand_description' => 'Curators of premium quiet luxury apparel, accessories, and structural lifestyle collectibles.',
            'contact_email' => 'concierge@shopme.com',
            'contact_phone' => '+1 (800) 555-0199',
            'contact_address' => '100 Quiet Luxury Way, Suite 400, Milan, Italy',
            'business_hours' => 'Mon - Fri: 9:00 AM - 6:00 PM CET',
            'instagram_url' => 'https://instagram.com/shopme',
            'facebook_url' => 'https://facebook.com/shopme',
            'pinterest_url' => 'https://pinterest.com/shopme',
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
