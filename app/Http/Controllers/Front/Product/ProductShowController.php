<?php

namespace App\Http\Controllers\Front\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ProductShowController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::with(['images', 'category', 'brand', 'reviews.user', 'variants.attributeValues.attribute'])
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        $relatedProducts = Product::with('featuredImage')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', true)
            ->take(4)
            ->get();

        return view('store.product', compact('product', 'relatedProducts'));
    }

    public function storeReview(Request $request, Product $product)
    {
        if (!$request->user()) {
            return back()->with('error', 'Please log in to submit a review.');
        }

        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        Review::create([
            'product_id' => $product->id,
            'user_id' => $request->user()->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => true, // Auto-approve for simplicity
        ]);

        return back()->with('success', 'Thank you for your review!');
    }
}
