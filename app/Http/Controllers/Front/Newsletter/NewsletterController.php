<?php

namespace App\Http\Controllers\Front\Newsletter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    /**
     * Store a newly created subscriber in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide a valid email address.'
            ], 422);
        }

        $email = strtolower(trim($request->email));

        // Check for duplicates and handle gracefully
        $existing = NewsletterSubscriber::where('email', $email)->first();
        if ($existing) {
            return response()->json([
                'success' => true, // Return true but with a friendly message
                'message' => "You're already subscribed to our inner circle!"
            ], 200);
        }

        try {
            NewsletterSubscriber::create([
                'email' => $email,
                'ip_address' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Welcome to the inner circle. Thank you for subscribing!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }
}
