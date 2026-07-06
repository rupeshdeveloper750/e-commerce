<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create a bunch of realistic customers
        $customersData = [
            ['name' => 'Rupesh Kumar', 'email' => 'rupesh@example.com'],
            ['name' => 'Amit Sharma', 'email' => 'amit@example.com'],
            ['name' => 'Rahul Singh', 'email' => 'rahul@example.com'],
            ['name' => 'Neha Verma', 'email' => 'neha@example.com'],
            ['name' => 'Priya Patel', 'email' => 'priya@example.com'],
            ['name' => 'Vikram Aditya', 'email' => 'vikram@example.com'],
            ['name' => 'Sanjay Dutt', 'email' => 'sanjay@example.com'],
            ['name' => 'Anjali Gupta', 'email' => 'anjali@example.com'],
            ['name' => 'Deepak Mishra', 'email' => 'deepak@example.com'],
            ['name' => 'Sneha Reddy', 'email' => 'sneha@example.com'],
            ['name' => 'Rohan Joshi', 'email' => 'rohan@example.com'],
            ['name' => 'Karan Malhotra', 'email' => 'karan@example.com'],
            ['name' => 'Meera Nair', 'email' => 'meera@example.com'],
            ['name' => 'Alok Pandey', 'email' => 'alok@example.com'],
            ['name' => 'Shweta Tiwari', 'email' => 'shweta@example.com']
        ];

        $users = [];
        foreach ($customersData as $index => $data) {
            $users[] = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password123'),
                    'status' => true,
                    'created_at' => now()->subDays(rand(5, 55)) // Register them over last 2 months
                ]
            );
        }

        // Get all products to build order items
        $products = Product::all();
        if ($products->isEmpty()) {
            return;
        }

        // Order configuration helper arrays
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        $paymentMethods = ['cod', 'card'];
        $cities = ['Mumbai', 'Delhi', 'Bangalore', 'Hyderabad', 'Ahmedabad', 'Chennai', 'Kolkata', 'Pune'];
        $states = ['Maharashtra', 'Delhi', 'Karnataka', 'Telangana', 'Gujarat', 'Tamil Nadu', 'West Bengal', 'Maharashtra'];

        // Generate around 60 orders spread over the last 60 days
        for ($i = 0; $i < 65; $i++) {
            $user = $users[array_rand($users)];
            
            // Random date in the last 60 days
            $orderDate = now()->subDays(rand(0, 60))->subHours(rand(1, 23))->subMinutes(rand(1, 59));
            
            $status = $statuses[array_rand($statuses)];
            // Adjust payment status based on delivery status
            if ($status === 'delivered') {
                $paymentStatus = 'paid';
            } elseif ($status === 'cancelled') {
                $paymentStatus = 'failed';
            } else {
                $paymentStatus = rand(0, 100) > 40 ? 'paid' : 'pending';
            }

            $paymentMethod = $paymentMethods[array_rand($paymentMethods)];

            // Select 1 to 4 random products
            $numItems = rand(1, 3);
            $selectedProducts = $products->random($numItems);

            $subtotal = 0;
            $itemsData = [];

            foreach ($selectedProducts as $product) {
                $qty = rand(1, 2);
                $price = $product->sale_price ?? $product->price;
                $itemSubtotal = $price * $qty;
                $subtotal += $itemSubtotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $price,
                    'quantity' => $qty,
                    'subtotal' => $itemSubtotal
                ];
            }

            // Discount: 20% of orders have discount
            $discount = 0;
            if (rand(0, 100) > 80) {
                $discount = round($subtotal * 0.10, 2); // 10% discount
            }

            // Shipping: 50 for orders below 1000, free above
            $shipping = $subtotal > 2000 ? 0.00 : 99.00;
            $total = $subtotal - $discount + $shipping;

            $cityIndex = array_rand($cities);
            
            $nameParts = explode(' ', $user->name);
            $firstName = $nameParts[0];
            $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . strtoupper(Str::random(3)) . '-' . rand(100000, 999999),
                'subtotal' => $subtotal,
                'discount' => $discount,
                'shipping' => $shipping,
                'total' => $total,
                'status' => $status,
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $user->email,
                'phone' => '98765' . rand(10000, 99999),
                'address' => rand(10, 250) . ', Main Street, Phase ' . rand(1, 5),
                'city' => $cities[$cityIndex],
                'state' => $states[$cityIndex],
                'zip_code' => '400' . rand(100, 999),
                'created_at' => $orderDate,
                'updated_at' => $orderDate
            ]);

            foreach ($itemsData as $item) {
                $item['order_id'] = $order->id;
                $item['created_at'] = $orderDate;
                $item['updated_at'] = $orderDate;
                OrderItem::create($item);
            }
        }
    }
}
