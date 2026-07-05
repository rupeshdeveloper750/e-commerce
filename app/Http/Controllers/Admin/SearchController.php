<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        // Require any admin gate access
        if (!auth('admin')->check()) {
            return response()->json([], 403);
        }

        $query = $request->input('q', '');
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $results = [];

        // Search Products
        if (Gate::allows('manage-products')) {
            $products = Product::where('name', 'like', '%' . $query . '%')
                ->orWhere('sku', 'like', '%' . $query . '%')
                ->limit(5)
                ->get();
            foreach ($products as $p) {
                $results[] = [
                    'category' => 'Products',
                    'title' => $p->name . ' (SKU: ' . $p->sku . ')',
                    'url' => route('admin.products.edit', $p->id),
                    'meta' => 'Price: ₹' . $p->price
                ];
            }
        }

        // Search Categories
        if (Gate::allows('manage-categories')) {
            $categories = Category::where('name', 'like', '%' . $query . '%')
                ->limit(3)
                ->get();
            foreach ($categories as $c) {
                $results[] = [
                    'category' => 'Categories',
                    'title' => $c->name,
                    'url' => route('admin.categories.edit', $c->id),
                    'meta' => $c->slug
                ];
            }
        }

        // Search Brands
        if (Gate::allows('manage-brands')) {
            $brands = Brand::where('name', 'like', '%' . $query . '%')
                ->limit(3)
                ->get();
            foreach ($brands as $b) {
                $results[] = [
                    'category' => 'Brands',
                    'title' => $b->name,
                    'url' => route('admin.brands.edit', $b->id),
                    'meta' => $b->slug
                ];
            }
        }

        // Search Orders
        if (Gate::allows('manage-orders')) {
            $orders = Order::where('order_number', 'like', '%' . $query . '%')
                ->orWhere('payment_status', 'like', '%' . $query . '%')
                ->limit(5)
                ->get();
            foreach ($orders as $o) {
                $results[] = [
                    'category' => 'Orders',
                    'title' => 'Order #' . $o->order_number,
                    'url' => route('admin.orders.show', $o->id),
                    'meta' => 'Total: ₹' . $o->total_amount . ' | ' . strtoupper($o->status)
                ];
            }
        }

        // Search Customers
        if (Gate::allows('manage-users')) {
            $users = User::where('name', 'like', '%' . $query . '%')
                ->orWhere('email', 'like', '%' . $query . '%')
                ->limit(5)
                ->get();
            foreach ($users as $u) {
                $results[] = [
                    'category' => 'Customers',
                    'title' => $u->name,
                    'url' => route('admin.customers.edit', $u->id),
                    'meta' => $u->email
                ];
            }
        }

        // Search Blogs
        if (Gate::allows('manage-blogs')) {
            $blogs = Blog::where('title', 'like', '%' . $query . '%')
                ->limit(3)
                ->get();
            foreach ($blogs as $bl) {
                $results[] = [
                    'category' => 'Blogs',
                    'title' => $bl->title,
                    'url' => route('admin.blogs.edit', $bl->id),
                    'meta' => 'Published: ' . ($bl->status ? 'Yes' : 'No')
                ];
            }
        }

        return response()->json($results);
    }
}
