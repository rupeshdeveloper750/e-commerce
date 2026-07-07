<?php

use App\Http\Controllers\Front\Home\HomeController;
use App\Http\Controllers\Front\Shop\ShopController;
use App\Http\Controllers\Front\Product\ProductShowController;
use App\Http\Controllers\Front\Cart\CartController;
use App\Http\Controllers\Front\Checkout\CheckoutController;
use App\Http\Controllers\User\Dashboard\UserDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', [HomeController::class, 'index'])->name('store.home');

// Shop & Catalog
Route::get('/shop', [ShopController::class, 'index'])->name('store.shop');
Route::get('/product/{slug}', [ProductShowController::class, 'show'])->name('store.product.show');
Route::post('/product/{product}/review', [ProductShowController::class, 'storeReview'])->name('store.product.review');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('store.cart');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('store.cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('store.cart.update');
Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('store.cart.remove');

// Dynamic DB API Cart Endpoints
Route::get('/api/cart', [CartController::class, 'apiGet'])->name('api.cart.get');
Route::patch('/api/cart/{id}', [CartController::class, 'apiUpdate'])->name('api.cart.update');
Route::delete('/api/cart/{id}', [CartController::class, 'apiRemove'])->name('api.cart.remove');
Route::post('/api/cart/save-later/{id}', [CartController::class, 'apiSaveLater'])->name('api.cart.saveLater');
Route::post('/api/cart/move-bag/{id}', [CartController::class, 'apiMoveToBag'])->name('api.cart.moveToBag');
Route::post('/api/cart/apply-coupon', [CartController::class, 'apiApplyCoupon'])->name('api.cart.applyCoupon');

// Checkout (auth protected)
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('store.checkout');
    Route::post('/checkout/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('store.coupon.apply');
    Route::get('/checkout/remove-coupon', [CheckoutController::class, 'removeCoupon'])->name('store.coupon.remove');
    Route::post('/checkout/place', [CheckoutController::class, 'placeOrder'])->name('store.checkout.place');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('store.order.success');

    // Customer Account Dashboard & Wishlist
    Route::get('/my-account', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::post('/wishlist/add/{product}', [UserDashboardController::class, 'addToWishlist'])->name('user.wishlist.add');
    Route::delete('/wishlist/remove/{id}', [UserDashboardController::class, 'removeFromWishlist'])->name('user.wishlist.remove');

    // Profile (Breeze default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Standard dashboard fallback redirects to customer portal
Route::get('/dashboard', function () {
    return redirect()->route('user.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

use App\Http\Controllers\Front\Newsletter\NewsletterController;

Route::post('/newsletter/subscribe', [NewsletterController::class, 'store'])->name('newsletter.subscribe');

require __DIR__.'/auth.php';
