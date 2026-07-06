<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CalculateBestsellers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ecommerce:calculate-bestsellers {--force : Force calculation regardless of configuration mode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically calculate and update products bestseller status based on weighted sales data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $mode = config('ecommerce.bestseller_mode', 'manual');

        if ($mode !== 'automatic' && !$this->option('force')) {
            $this->info('Bestseller calculation skipped. Currently set to manual curation mode.');
            $this->info('Use --force flag to run calculation anyway.');
            return 0;
        }

        $this->info('Calculating bestseller rankings based on 90-day weighted sales...');

        // Date ranges
        $now = Carbon::now();
        $thirtyDaysAgo = $now->copy()->subDays(30);
        $sixtyDaysAgo = $now->copy()->subDays(60);
        $ninetyDaysAgo = $now->copy()->subDays(90);

        // Retrieve active products with stock >= 5
        $eligibleProducts = Product::where('status', true)
            ->where('quantity', '>=', 5)
            ->get();

        $rankings = [];

        foreach ($eligibleProducts as $product) {
            // Fetch order items within last 90 days for non-cancelled orders
            $orderItems = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('order_items.product_id', $product->id)
                ->where('orders.status', '!=', 'cancelled')
                ->where('orders.created_at', '>=', $ninetyDaysAgo)
                ->select('order_items.quantity', 'orders.created_at')
                ->get();

            $score = 0.0;

            foreach ($orderItems as $item) {
                $createdAt = Carbon::parse($item->created_at);
                
                // Determine decay weight factor
                if ($createdAt->gte($thirtyDaysAgo)) {
                    $weight = 1.5;
                } elseif ($createdAt->gte($sixtyDaysAgo)) {
                    $weight = 1.0;
                } else {
                    $weight = 0.5;
                }

                $score += $item->quantity * $weight;
            }

            if ($score > 0) {
                $rankings[] = [
                    'product_id' => $product->id,
                    'score' => $score
                ];
            }
        }

        // Sort rankings by score descending
        usort($rankings, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // Configured limit (default top 8)
        $limit = 8;
        $topRankings = array_slice($rankings, 0, $limit);
        $topProductIds = array_column($topRankings, 'product_id');

        DB::transaction(function () use ($topProductIds) {
            // Unset bestsellers flag for all products first
            Product::where('is_bestseller', true)->update([
                'is_bestseller' => false,
                'bestseller_sort_order' => null
            ]);

            // Set bestseller flag and ordering for ranked products
            foreach ($topProductIds as $index => $productId) {
                Product::where('id', $productId)->update([
                    'is_bestseller' => true,
                    'bestseller_sort_order' => $index + 1
                ]);
            }
        });

        $this->info('Successfully calculated and updated bestseller product statuses.');
        foreach ($topProductIds as $index => $productId) {
            $productName = Product::find($productId)->name;
            $this->line("Rank " . ($index + 1) . ": " . $productName);
        }

        return 0;
    }
}
