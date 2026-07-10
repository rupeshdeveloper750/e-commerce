<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductVariant;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Brands
        $brandsData = [
            ['name' => 'Nike', 'slug' => 'nike'],
            ['name' => 'Adidas', 'slug' => 'adidas'],
            ['name' => 'Apple', 'slug' => 'apple'],
            ['name' => 'Samsung', 'slug' => 'samsung'],
            ['name' => 'Zara', 'slug' => 'zara'],
            ['name' => 'Casio', 'slug' => 'casio'],
            ['name' => 'Samsonite', 'slug' => 'samsonite'],
            ['name' => 'Sony', 'slug' => 'sony']
        ];

        $brands = [];
        foreach ($brandsData as $b) {
            $brands[$b['slug']] = Brand::updateOrCreate(
                ['slug' => $b['slug']],
                [
                    'name' => $b['name'],
                    'description' => $b['name'] . ' Brand',
                    'status' => true,
                    'is_featured' => true
                ]
            );
        }

        // Ensure target products directory exists
        Storage::disk('public')->makeDirectory('products');

        // Unsplash Images for each parent category
        $imagesMap = [
            'fashion' => [
                'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1479064555552-3ef4979f8908?w=600&auto=format&fit=crop&q=80',
            ],
            'electronics' => [
                'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1496181130204-7552cc14ac1b?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&auto=format&fit=crop&q=80',
            ],
            'footwear' => [
                'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1539185441755-769473a23570?w=600&auto=format&fit=crop&q=80',
            ],
            'watches' => [
                'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1524592094714-0f0654e20314?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=600&auto=format&fit=crop&q=80',
            ],
            'bags' => [
                'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?w=600&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1581605405669-fcdf81165afa?w=600&auto=format&fit=crop&q=80',
            ]
        ];

        // Let's pre-download these images to local public disk under products directory
        $localImages = [];
        foreach ($imagesMap as $parentSlug => $urls) {
            $localImages[$parentSlug] = [];
            foreach ($urls as $index => $url) {
                $filename = 'products/' . $parentSlug . '_' . ($index + 1) . '.jpg';
                
                if (!Storage::disk('public')->exists($filename)) {
                    try {
                        $response = Http::timeout(10)->get($url);
                        if ($response->successful()) {
                            Storage::disk('public')->put($filename, $response->body());
                        }
                    } catch (\Exception $e) {
                        // Fallback: create a simple colored canvas image if download fails
                        $im = imagecreatetruecolor(600, 600);
                        $bg = imagecolorallocate($im, rand(50, 200), rand(50, 200), rand(50, 200));
                        imagefill($im, 0, 0, $bg);
                        ob_start();
                        imagejpeg($im);
                        $imgData = ob_get_clean();
                        imagedestroy($im);
                        Storage::disk('public')->put($filename, $imgData);
                    }
                }
                $localImages[$parentSlug][] = $filename;
            }
        }

        // Product list definitions
        $productsDefinition = [
            // Fashion subcategories
            'men-clothing' => [
                'brand' => 'zara',
                'items' => [
                    ['name' => 'Slim Fit Denim Shirt', 'price' => 1899, 'sale' => 1499],
                    ['name' => 'Classic Crewneck T-Shirt', 'price' => 799, 'sale' => 499],
                    ['name' => 'Chino Trousers', 'price' => 2499, 'sale' => 1999],
                    ['name' => 'Bomber Jacket', 'price' => 3999, 'sale' => 2999],
                    ['name' => 'Linen Casual Blazer', 'price' => 4999, 'sale' => 3999],
                    ['name' => 'Graphic Printed Tee', 'price' => 899, 'sale' => 599],
                    ['name' => 'Wool Blend Sweater', 'price' => 2799, 'sale' => 2199],
                    ['name' => 'Cargo Shorts', 'price' => 1599, 'sale' => 1299]
                ]
            ],
            'women-clothing' => [
                'brand' => 'zara',
                'items' => [
                    ['name' => 'Floral Summer Dress', 'price' => 2999, 'sale' => 2499],
                    ['name' => 'High Waist Skinny Jeans', 'price' => 2299, 'sale' => 1799],
                    ['name' => 'Knit Cardigan', 'price' => 1999, 'sale' => 1499],
                    ['name' => 'Oversized Hoodie', 'price' => 2499, 'sale' => 1899],
                    ['name' => 'Satin Slip Skirt', 'price' => 1799, 'sale' => 1399],
                    ['name' => 'Off-Shoulder Top', 'price' => 1299, 'sale' => 999],
                    ['name' => 'Trench Coat', 'price' => 5999, 'sale' => 4599],
                    ['name' => 'Linen Button-Down Shirt', 'price' => 1699, 'sale' => 1299]
                ]
            ],
            'kids-wear' => [
                'brand' => 'zara',
                'items' => [
                    ['name' => 'Toddler Cotton Romper', 'price' => 899, 'sale' => 699],
                    ['name' => 'Kids Cartoon Print Hoodie', 'price' => 1499, 'sale' => 1199],
                    ['name' => 'Denim Dungarees', 'price' => 1799, 'sale' => 1299],
                    ['name' => 'Toddler Soft Pajama Set', 'price' => 999, 'sale' => 799],
                    ['name' => 'Kids Fleece Jacket', 'price' => 1999, 'sale' => 1499],
                    ['name' => 'Girl Floral Party Dress', 'price' => 2199, 'sale' => 1699],
                    ['name' => 'Boy Striped Polo Shirt', 'price' => 799, 'sale' => 599],
                    ['name' => 'Kids Activewear Shorts', 'price' => 699, 'sale' => 499]
                ]
            ],
            'accessories' => [
                'brand' => 'zara',
                'items' => [
                    ['name' => 'Polarized Wayfarer Sunglasses', 'price' => 1599, 'sale' => 1199],
                    ['name' => 'Reversible Leather Belt', 'price' => 1299, 'sale' => 899],
                    ['name' => 'Silk Floral Scarf', 'price' => 999, 'sale' => 699],
                    ['name' => 'Knitted Beanie Hat', 'price' => 599, 'sale' => 399],
                    ['name' => 'Canvas Baseball Cap', 'price' => 799, 'sale' => 499],
                    ['name' => 'Leather Wallet', 'price' => 1499, 'sale' => 1099],
                    ['name' => 'Wool Blend Socks (3-Pack)', 'price' => 499, 'sale' => 349],
                    ['name' => 'Metal Frame Aviators', 'price' => 1899, 'sale' => 1399]
                ]
            ],

            // Electronics subcategories
            'mobiles' => [
                'brand' => 'samsung',
                'items' => [
                    ['name' => 'Galaxy S24 Ultra', 'price' => 124999, 'sale' => 119999],
                    ['name' => 'iPhone 15 Pro', 'price' => 134900, 'sale' => 129900],
                    ['name' => 'Galaxy A55 5G', 'price' => 39999, 'sale' => 34999],
                    ['name' => 'Pixel 8 Pro', 'price' => 109999, 'sale' => 99999],
                    ['name' => 'OnePlus 12', 'price' => 64999, 'sale' => 61999],
                    ['name' => 'Redmi Note 13 Pro', 'price' => 25999, 'sale' => 23999],
                    ['name' => 'Nothing Phone 2', 'price' => 44999, 'sale' => 39999],
                    ['name' => 'Galaxy Z Flip 5', 'price' => 99999, 'sale' => 89999]
                ]
            ],
            'laptops' => [
                'brand' => 'apple',
                'items' => [
                    ['name' => 'MacBook Air M3', 'price' => 114900, 'sale' => 104900],
                    ['name' => 'Dell XPS 13', 'price' => 139999, 'sale' => 129999],
                    ['name' => 'HP Spectre x360', 'price' => 149999, 'sale' => 139999],
                    ['name' => 'Lenovo ThinkPad X1 Carbon', 'price' => 179999, 'sale' => 169999],
                    ['name' => 'ASUS ROG Zephyrus G14', 'price' => 144999, 'sale' => 134999],
                    ['name' => 'MacBook Pro 14 M3 Pro', 'price' => 199900, 'sale' => 189900],
                    ['name' => 'Acer Swift Go 14', 'price' => 69999, 'sale' => 59999],
                    ['name' => 'MSI Modern 14', 'price' => 45999, 'sale' => 39999]
                ]
            ],
            'tablets' => [
                'brand' => 'apple',
                'items' => [
                    ['name' => 'iPad Air M2', 'price' => 59900, 'sale' => 56900],
                    ['name' => 'Galaxy Tab S9 FE', 'price' => 36999, 'sale' => 32999],
                    ['name' => 'iPad Pro M4', 'price' => 99900, 'sale' => 94900],
                    ['name' => 'Lenovo Tab P12', 'price' => 25999, 'sale' => 22999],
                    ['name' => 'iPad 10th Gen', 'price' => 39900, 'sale' => 34900],
                    ['name' => 'Galaxy Tab A9+', 'price' => 18999, 'sale' => 15999],
                    ['name' => 'Xiaomi Pad 6', 'price' => 26999, 'sale' => 24999],
                    ['name' => 'OnePlus Pad Go', 'price' => 19999, 'sale' => 17999]
                ]
            ],
            'headphones' => [
                'brand' => 'sony',
                'items' => [
                    ['name' => 'Sony WH-1000XM5', 'price' => 29990, 'sale' => 26990],
                    ['name' => 'AirPods Pro 2', 'price' => 24900, 'sale' => 22900],
                    ['name' => 'Bose QuietComfort Ultra', 'price' => 35900, 'sale' => 32900],
                    ['name' => 'Sennheiser Accentum', 'price' => 11990, 'sale' => 9990],
                    ['name' => 'OnePlus Buds 3', 'price' => 5499, 'sale' => 4999],
                    ['name' => 'JBL Tune 770NC', 'price' => 6999, 'sale' => 5499],
                    ['name' => 'Sony WF-1000XM5', 'price' => 23990, 'sale' => 19990],
                    ['name' => 'boAt Rockerz 450 Pro', 'price' => 1999, 'sale' => 1499]
                ]
            ],

            // Footwear subcategories
            'sneakers' => [
                'brand' => 'nike',
                'items' => [
                    ['name' => 'Air Force 1 \'07', 'price' => 9695, 'sale' => 8995],
                    ['name' => 'Samba OG Shoes', 'price' => 10999, 'sale' => 9999],
                    ['name' => 'Air Max SYSTM', 'price' => 8495, 'sale' => 7495],
                    ['name' => 'Stan Smith Classic', 'price' => 8999, 'sale' => 7999],
                    ['name' => 'Ultraboost Light', 'price' => 18999, 'sale' => 15999],
                    ['name' => 'Court Royale 2 Low', 'price' => 4995, 'sale' => 3995],
                    ['name' => 'Superstar Slip-On', 'price' => 7999, 'sale' => 6999],
                    ['name' => 'Dunk Low Retro', 'price' => 8295, 'sale' => 7895]
                ]
            ],
            'sports-shoes' => [
                'brand' => 'adidas',
                'items' => [
                    ['name' => 'Pegasus 41 Road Running', 'price' => 11495, 'sale' => 9995],
                    ['name' => 'Supernova Rise Running', 'price' => 10999, 'sale' => 8999],
                    ['name' => 'Downshifter 13', 'price' => 4295, 'sale' => 3495],
                    ['name' => 'Response Runner', 'price' => 4599, 'sale' => 3799],
                    ['name' => 'Zoom Fly 5', 'price' => 14995, 'sale' => 11995],
                    ['name' => 'Duramo Speed', 'price' => 7599, 'sale' => 5999],
                    ['name' => 'InfinityRN 4', 'price' => 14995, 'sale' => 12995],
                    ['name' => 'Galaxy 6 Running', 'price' => 5299, 'sale' => 4299]
                ]
            ],
            'formal-shoes' => [
                'brand' => 'nike',
                'items' => [
                    ['name' => 'Classic Derby Leather Shoes', 'price' => 3999, 'sale' => 2999],
                    ['name' => 'Oxford Brogue Shoes', 'price' => 4500, 'sale' => 3499],
                    ['name' => 'Italian Leather Monk Straps', 'price' => 5999, 'sale' => 4999],
                    ['name' => 'Penny Loafers Premium', 'price' => 3799, 'sale' => 2999],
                    ['name' => 'Suede Slip-On Loafers', 'price' => 3499, 'sale' => 2699],
                    ['name' => 'Cap-Toe Oxford Shoes', 'price' => 4999, 'sale' => 3999],
                    ['name' => 'Wingtip Brogues', 'price' => 4799, 'sale' => 3799],
                    ['name' => 'Chelsea Boots Black', 'price' => 6999, 'sale' => 5499]
                ]
            ],
            'sandals' => [
                'brand' => 'adidas',
                'items' => [
                    ['name' => 'Adilette Shower Slides', 'price' => 2499, 'sale' => 1899],
                    ['name' => 'Comfort Slide Sandals', 'price' => 2299, 'sale' => 1699],
                    ['name' => 'Adventure Strap Sandals', 'price' => 4999, 'sale' => 3999],
                    ['name' => 'Oneonta Trail Sandals', 'price' => 5995, 'sale' => 4995],
                    ['name' => 'Victori One Slides', 'price' => 2695, 'sale' => 1995],
                    ['name' => 'Terrex Sumra Sandals', 'price' => 6999, 'sale' => 5599],
                    ['name' => 'Asuna 2 Slides', 'price' => 3695, 'sale' => 2995],
                    ['name' => 'Alphabounce Slides', 'price' => 3299, 'sale' => 2499]
                ]
            ],

            // Watches subcategories
            'analog' => [
                'brand' => 'casio',
                'items' => [
                    ['name' => 'Enticer Men\'s Blue Dial', 'price' => 3995, 'sale' => 3195],
                    ['name' => 'Classic Dress Gold Watch', 'price' => 5495, 'sale' => 4395],
                    ['name' => 'Minimalist Black Leather', 'price' => 2995, 'sale' => 2395],
                    ['name' => 'Chronograph Silver Dial', 'price' => 8995, 'sale' => 7195],
                    ['name' => 'Saphire Crystal Slim', 'price' => 12995, 'sale' => 9995],
                    ['name' => 'Enticer Ladies Pink Dial', 'price' => 3495, 'sale' => 2795],
                    ['name' => 'Vintage Series Analog', 'price' => 4995, 'sale' => 3995],
                    ['name' => 'Edifice Solar Powered', 'price' => 15995, 'sale' => 12795]
                ]
            ],
            'digital' => [
                'brand' => 'casio',
                'items' => [
                    ['name' => 'Vintage Digital Gold D182', 'price' => 5995, 'sale' => 4795],
                    ['name' => 'G-Shock Digital Sport', 'price' => 7995, 'sale' => 6395],
                    ['name' => 'Classic F91W-1', 'price' => 1095, 'sale' => 995],
                    ['name' => 'Youth World Time Digital', 'price' => 2995, 'sale' => 2395],
                    ['name' => 'G-Shock Mudmaster Digital', 'price' => 21995, 'sale' => 18995],
                    ['name' => 'Vintage Silver A158WA', 'price' => 1695, 'sale' => 1495],
                    ['name' => 'Digital Tide Graph Watch', 'price' => 4500, 'sale' => 3595],
                    ['name' => 'Outdoor Rugged Digital', 'price' => 3995, 'sale' => 3195]
                ]
            ],
            'smart-watches' => [
                'brand' => 'apple',
                'items' => [
                    ['name' => 'Apple Watch Series 9 GPS', 'price' => 41900, 'sale' => 38900],
                    ['name' => 'Galaxy Watch 6 Bluetooth', 'price' => 29999, 'sale' => 26999],
                    ['name' => 'Apple Watch SE 2nd Gen', 'price' => 29900, 'sale' => 26900],
                    ['name' => 'Galaxy Watch 6 Classic', 'price' => 36999, 'sale' => 32999],
                    ['name' => 'Apple Watch Ultra 2 GPS', 'price' => 89900, 'sale' => 84900],
                    ['name' => 'Amazfit GTR 4 Smartwatch', 'price' => 16999, 'sale' => 14999],
                    ['name' => 'Fitbit Sense 2 Health', 'price' => 22999, 'sale' => 19999],
                    ['name' => 'Garmin Venu 3 GPS Watch', 'price' => 44990, 'sale' => 39990]
                ]
            ],
            'luxury' => [
                'brand' => 'casio',
                'items' => [
                    ['name' => 'Oceanus Premium Solar Chrono', 'price' => 85000, 'sale' => 79000],
                    ['name' => 'G-Shock MT-G Luxury Edition', 'price' => 95000, 'sale' => 89000],
                    ['name' => 'G-Shock Full Metal Gold', 'price' => 49995, 'sale' => 44995],
                    ['name' => 'Edifice Premium Sapphire Line', 'price' => 24995, 'sale' => 19995],
                    ['name' => 'Oceanus Classic Blue Dial', 'price' => 72000, 'sale' => 68000],
                    ['name' => 'G-Shock MR-G Hand Crafted', 'price' => 280000, 'sale' => 260000],
                    ['name' => 'Edifice Honda Racing Limited', 'price' => 32995, 'sale' => 29995],
                    ['name' => 'Pro Trek Premium Titanium', 'price' => 39995, 'sale' => 34995]
                ]
            ],

            // Bags subcategories
            'hand-bags' => [
                'brand' => 'samsonite',
                'items' => [
                    ['name' => 'Classic Leather Satchel Bag', 'price' => 4999, 'sale' => 3999],
                    ['name' => 'Tote Shopper Bag Premium', 'price' => 2999, 'sale' => 2299],
                    ['name' => 'Structured Shoulder Bag', 'price' => 3499, 'sale' => 2799],
                    ['name' => 'Crossbody Messenger Bag', 'price' => 1999, 'sale' => 1499],
                    ['name' => 'Sling Chain Hand Bag', 'price' => 2499, 'sale' => 1899],
                    ['name' => 'Designer Hobo Hand Bag', 'price' => 4500, 'sale' => 3499],
                    ['name' => 'Vegan Leather Dome Satchel', 'price' => 3799, 'sale' => 2999],
                    ['name' => 'Quilted Chain Shoulder Bag', 'price' => 3299, 'sale' => 2499]
                ]
            ],
            'backpacks' => [
                'brand' => 'samsonite',
                'items' => [
                    ['name' => 'Classic Campus Backpack', 'price' => 2499, 'sale' => 1999],
                    ['name' => 'Water Resistant Travel Pack', 'price' => 3999, 'sale' => 2999],
                    ['name' => 'Minimalist Daily Backpack', 'price' => 1899, 'sale' => 1399],
                    ['name' => 'Heavy Duty Commuter Pack', 'price' => 4500, 'sale' => 3499],
                    ['name' => 'Outdoor Hiking Rucksack 45L', 'price' => 5999, 'sale' => 4799],
                    ['name' => 'Roll-Top Laptop Backpack', 'price' => 3299, 'sale' => 2499],
                    ['name' => 'Compact Anti-Theft Pack', 'price' => 2799, 'sale' => 1999],
                    ['name' => 'Casual Canvas Daypack', 'price' => 1599, 'sale' => 1199]
                ]
            ],
            'travel-bags' => [
                'brand' => 'samsonite',
                'items' => [
                    ['name' => 'Premium Spinner Suitcase 24"', 'price' => 12999, 'sale' => 9999],
                    ['name' => 'Cabin Hard-Shell Trolley 20"', 'price' => 8999, 'sale' => 6999],
                    ['name' => 'Leather Weekender Duffle Bag', 'price' => 5999, 'sale' => 4999],
                    ['name' => 'Foldable Nylon Gym Bag', 'price' => 1499, 'sale' => 999],
                    ['name' => 'Rolling Garment Carrier Bag', 'price' => 7499, 'sale' => 5999],
                    ['name' => 'Underseat Carry-On Trolley', 'price' => 4999, 'sale' => 3999],
                    ['name' => 'Adventure Duffel with Wheels', 'price' => 6499, 'sale' => 4999],
                    ['name' => 'Hard Shell Large Luggage 28"', 'price' => 15999, 'sale' => 12999]
                ]
            ],
            'laptop-bags' => [
                'brand' => 'samsonite',
                'items' => [
                    ['name' => 'Classic Laptop Briefcase 15.6"', 'price' => 2999, 'sale' => 2299],
                    ['name' => 'Slim Profile Laptop Sleeve 14"', 'price' => 999, 'sale' => 799],
                    ['name' => 'Executive Leather Laptop Bag', 'price' => 6999, 'sale' => 5499],
                    ['name' => 'Messenger Laptop Shoulder Bag', 'price' => 2499, 'sale' => 1899],
                    ['name' => 'Waterproof Commuter Sleeve 15"', 'price' => 1299, 'sale' => 999],
                    ['name' => 'Convertible Backpack Briefcase', 'price' => 4999, 'sale' => 3999],
                    ['name' => 'Shockproof EVA Hard Shell Case', 'price' => 1799, 'sale' => 1299],
                    ['name' => 'Multi-Pocket Tech Briefcase', 'price' => 3499, 'sale' => 2699]
                ]
            ]
        ];

        // Seed products
        foreach ($productsDefinition as $subSlug => $data) {
            // Find the subcategory
            $category = Category::where('slug', $subSlug)->first();
            if (!$category) {
                continue;
            }

            // Find parent category to determine which images to use
            $parentCategory = $category->parent;
            $parentSlug = $parentCategory ? Str::slug($parentCategory->name) : 'fashion';
            
            $brand = $brands[$data['brand']] ?? null;

            foreach ($data['items'] as $itemIndex => $item) {
                $slug = Str::slug($item['name']);
                
                $product = Product::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'category_id' => $category->id,
                        'brand_id' => $brand ? $brand->id : null,
                        'name' => $item['name'],
                        'sku' => strtoupper(substr($parentSlug, 0, 3) . '-' . substr($subSlug, 0, 3) . '-' . str_pad($itemIndex + 1, 3, '0', STR_PAD_LEFT)),
                        'short_description' => 'Premium quality ' . $item['name'] . ' from the house of ' . ($brand ? $brand->name : 'ShopMe') . '.',
                        'description' => 'Introducing the ' . $item['name'] . '. Crafted with premium materials, this item ensures durability, style, and extreme comfort/utility. Perfect for daily use and special occasions. Buy the best ' . $item['name'] . ' online today!',
                        'price' => $item['price'],
                        'sale_price' => $item['sale'],
                        'quantity' => 100,
                        'status' => true,
                        'is_featured' => ($itemIndex === 0 || $itemIndex === 3), // Make first and fourth item featured
                        'meta_title' => $item['name'] . ' - Buy Online',
                        'meta_description' => 'Shop the premium ' . $item['name'] . ' at lowest prices.'
                    ]
                );

                // Assign images
                $categoryImages = $localImages[$parentSlug] ?? [];
                if (!empty($categoryImages)) {
                    // Delete existing images to avoid accumulation
                    $product->images()->delete();

                    // Add featured image
                    $featuredImageFilename = $categoryImages[($itemIndex) % count($categoryImages)];
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $featuredImageFilename,
                        'sort_order' => 0,
                        'is_featured' => true
                    ]);

                    // Add gallery image
                    $galleryImageFilename = $categoryImages[($itemIndex + 1) % count($categoryImages)];
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $galleryImageFilename,
                        'sort_order' => 1,
                        'is_featured' => false
                    ]);
                }

                // Generate variants for this product based on its category
                $parentSlug = strtolower($parentCategory ? $parentCategory->slug : $category->slug);
                $subSlug = strtolower($category->slug);
                
                $colors = [];
                $sizes = [];
                $storages = [];
                $capacities = [];
                
                if (str_contains($parentSlug, 'fashion')) {
                    $sizes = ['S', 'M', 'L'];
                    $colors = ['black', 'white', 'olive'];
                } elseif (str_contains($parentSlug, 'electronics')) {
                    if ($subSlug === 'headphones') {
                        $colors = ['black', 'silver'];
                    } else {
                        $isLaptop = ($subSlug === 'laptops');
                        $storages = $isLaptop ? ['256GB', '512GB'] : ['128GB', '256GB', '512GB'];
                        $colors = ['space-gray', 'silver'];
                    }
                } elseif (str_contains($parentSlug, 'footwear')) {
                    $sizes = ['8', '9', '10'];
                    if ($subSlug === 'sneakers' || $subSlug === 'sports-shoes') {
                        $colors = ['red', 'green', 'tan'];
                    } else {
                        $colors = ['black', 'brown'];
                    }
                } elseif (str_contains($parentSlug, 'watches')) {
                    $colors = ['silver', 'gold', 'black'];
                } elseif (str_contains($parentSlug, 'bags')) {
                    $capacities = ['20L', '30L'];
                    $colors = ['black', 'tan'];
                }
                
                $variantsData = [];
                if (!empty($colors) && !empty($sizes)) {
                    foreach ($colors as $c) {
                        foreach ($sizes as $s) {
                            $variantsData[] = [
                                'suffix' => strtoupper($s . '-' . substr($c, 0, 3)),
                                'price_offset' => 0,
                                'attrs' => [['Size', $s], ['Color', $c]]
                            ];
                        }
                    }
                } elseif (!empty($colors) && !empty($storages)) {
                    foreach ($colors as $c) {
                        foreach ($storages as $st) {
                            $priceOffset = str_contains($st, '512') ? 8000 : (str_contains($st, '256') ? 4000 : 0);
                            $variantsData[] = [
                                'suffix' => strtoupper(substr($st, 0, 3) . '-' . substr($c, 0, 3)),
                                'price_offset' => $priceOffset,
                                'attrs' => [['Storage', $st], ['Color', $c]]
                            ];
                        }
                    }
                } elseif (!empty($colors) && !empty($capacities)) {
                    foreach ($colors as $c) {
                        foreach ($capacities as $cap) {
                            $priceOffset = str_contains($cap, '30L') ? 600 : 0;
                            $variantsData[] = [
                                'suffix' => strtoupper($cap . '-' . substr($c, 0, 3)),
                                'price_offset' => $priceOffset,
                                'attrs' => [['Capacity', $cap], ['Color', $c]]
                            ];
                        }
                    }
                } elseif (!empty($colors)) {
                    foreach ($colors as $c) {
                        $priceOffset = ($c === 'gold') ? 1500 : (($c === 'black') ? 500 : 0);
                        $variantsData[] = [
                            'suffix' => strtoupper(substr($c, 0, 3)),
                            'price_offset' => $priceOffset,
                            'attrs' => [['Color', $c]]
                        ];
                    }
                }

                $getColorImageIndex = function($pSlug, $colorName) {
                    $color = strtolower($colorName);
                    if (str_contains($pSlug, 'fashion')) {
                        if ($color === 'black' || $color === 'yellow') return 0;
                        if ($color === 'white' || $color === 'cream') return 1;
                        if ($color === 'olive' || $color === 'green') return 0;
                    }
                    if (str_contains($pSlug, 'electronics')) {
                        if (str_contains($color, 'gray') || str_contains($color, 'black')) return 0;
                        if (str_contains($color, 'silver') || str_contains($color, 'white')) return 1;
                    }
                    if (str_contains($pSlug, 'footwear')) {
                        if ($color === 'red') return 0;
                        if ($color === 'green' || $color === 'lime') return 1;
                        if ($color === 'tan' || $color === 'beige') return 2;
                        if ($color === 'black') return 0;
                        if ($color === 'brown') return 3;
                    }
                    if (str_contains($pSlug, 'watches')) {
                        if ($color === 'silver' || $color === 'white') return 0;
                        if ($color === 'gold') return 1;
                        if ($color === 'black') return 3;
                    }
                    if (str_contains($pSlug, 'bags')) {
                        if ($color === 'black') return 0;
                        if ($color === 'tan' || $color === 'brown') return 1;
                    }
                    return 0;
                };

                foreach ($variantsData as $vInfo) {
                    $vSku = $product->sku . '-' . $vInfo['suffix'];
                    
                    $colorValue = 'black';
                    foreach ($vInfo['attrs'] as $pair) {
                        if ($pair[0] === 'Color') {
                            $colorValue = $pair[1];
                            break;
                        }
                    }
                    
                    $imgIndex = $getColorImageIndex($parentSlug, $colorValue);
                    $vImagePath = null;
                    if (!empty($categoryImages)) {
                        $vImagePath = $categoryImages[$imgIndex % count($categoryImages)];
                    }
                    
                    $variant = ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => $vSku,
                        'price' => $product->price + $vInfo['price_offset'],
                        'sale_price' => $product->sale_price ? ($product->sale_price + $vInfo['price_offset']) : null,
                        'quantity' => 50,
                        'image_path' => $vImagePath
                    ]);
                    
                    $attrValIds = [];
                    foreach ($vInfo['attrs'] as $attrPair) {
                        $attr = Attribute::firstOrCreate(['name' => $attrPair[0]]);
                        $val = AttributeValue::firstOrCreate(['attribute_id' => $attr->id, 'value' => $attrPair[1]]);
                        $attrValIds[] = $val->id;
                    }
                    $variant->attributeValues()->sync($attrValIds);
                }
            }
        }
    }
}
