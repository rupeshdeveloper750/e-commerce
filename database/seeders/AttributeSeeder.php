<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attribute;
use App\Models\AttributeValue;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Size / Storage Attribute
        $storageAttr = Attribute::firstOrCreate(['name' => 'Storage']);
        $storageValues = ['128GB', '256GB', '512GB'];
        foreach ($storageValues as $val) {
            AttributeValue::firstOrCreate([
                'attribute_id' => $storageAttr->id,
                'value' => $val
            ]);
        }

        // 2. Create Size (Clothing/Shoes) Attribute
        $sizeAttr = Attribute::firstOrCreate(['name' => 'Size']);
        $sizeValues = ['XS', 'S', 'M', 'L', 'XL', 'XXL', '6', '7', '8', '9', '10', '11'];
        foreach ($sizeValues as $val) {
            AttributeValue::firstOrCreate([
                'attribute_id' => $sizeAttr->id,
                'value' => $val
            ]);
        }

        // 3. Create Color Attribute
        $colorAttr = Attribute::firstOrCreate(['name' => 'Color']);
        $colorValues = [
            'silver', 'space-gray', 'gold', 
            'cream', 'tan', 'dark', 
            'black', 'brown', 'white', 
            'natural', 'walnut', 'matte-black', 
            'olive'
        ];
        foreach ($colorValues as $val) {
            AttributeValue::firstOrCreate([
                'attribute_id' => $colorAttr->id,
                'value' => $val
            ]);
        }

        // 4. Create Capacity Attribute
        $capacityAttr = Attribute::firstOrCreate(['name' => 'Capacity']);
        $capacityValues = ['15L', '20L', '30L'];
        foreach ($capacityValues as $val) {
            AttributeValue::firstOrCreate([
                'attribute_id' => $capacityAttr->id,
                'value' => $val
            ]);
        }
    }
}
