<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['product', 'user']);

        if ($request->filled('search')) {
            $query->whereHas('product', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
                  ->orWhereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
                  ->orWhere('comment', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', (bool) $request->status);
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total'    => Review::count(),
            'approved' => Review::where('status', true)->count(),
            'pending'  => Review::where('status', false)->count(),
            'avg_rating' => round(Review::avg('rating'), 1),
        ];

        return view('admin.review.index', compact('reviews', 'stats'));
    }

    public function approve($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['status' => true]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Review approved.']);
        }
        return back()->with('success', 'Review approved successfully.');
    }

    public function reject($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['status' => false]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Review rejected.']);
        }
        return back()->with('success', 'Review rejected successfully.');
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return back()->with('success', 'Review deleted successfully.');
    }
}
