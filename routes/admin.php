<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Catalog\CategoryController;
use App\Http\Controllers\Admin\Catalog\BrandController;
use App\Http\Controllers\Admin\Catalog\ProductController;
use App\Http\Controllers\Admin\Catalog\InventoryController;
use App\Http\Controllers\Admin\Sales\CouponController;
use App\Http\Controllers\Admin\Sales\OrderController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\RoleController;
use App\Http\Controllers\Admin\Auth\PermissionController;
use App\Http\Controllers\Admin\Auth\AdminUserController;
use App\Http\Controllers\Admin\Customer\CustomerController;
use App\Http\Controllers\Admin\Marketing\BlogController;
use App\Http\Controllers\Admin\Marketing\BannerController;
use App\Http\Controllers\Admin\Setting\SettingController;
use App\Http\Controllers\Admin\Setting\ActivityLogController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Admin\Auth\ProfileController;
use App\Http\Controllers\Admin\Sales\ReviewController;
use App\Http\Controllers\Admin\Sales\TransactionController;
use App\Http\Controllers\Admin\Report\ReportController;
use App\Http\Controllers\Admin\Sales\SupportController;


    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('admin.login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->name('admin.login.store');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:admin')
    ->name('admin.logout');


Route::middleware('auth:admin')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard.dashboard');
    })->name('admin.dashboard');


    // Categories Extra Actions
    Route::post('categories/bulk-action', [CategoryController::class, 'bulkAction'])
        ->name('admin.categories.bulk-action');
    Route::post('categories/{id}/restore', [CategoryController::class, 'restore'])
        ->name('admin.categories.restore');
    Route::delete('categories/{id}/force-delete', [CategoryController::class, 'forceDelete'])
        ->name('admin.categories.force-delete');
    Route::get('categories/export', [CategoryController::class, 'export'])
        ->name('admin.categories.export');
    Route::post('categories/import', [CategoryController::class, 'import'])
        ->name('admin.categories.import');

    Route::resource('categories', CategoryController::class)
        ->names('admin.categories');

    // Brands Extra Actions
    Route::post('brands/bulk-action', [BrandController::class, 'bulkAction'])
        ->name('admin.brands.bulk-action');
    Route::post('brands/{id}/restore', [BrandController::class, 'restore'])
        ->name('admin.brands.restore');
    Route::delete('brands/{id}/force-delete', [BrandController::class, 'forceDelete'])
        ->name('admin.brands.force-delete');
    Route::get('brands/export', [BrandController::class, 'export'])
        ->name('admin.brands.export');
    Route::post('brands/import', [BrandController::class, 'import'])
        ->name('admin.brands.import');

    Route::resource('brands', BrandController::class)
        ->names('admin.brands');

    // Products Extra Actions
    Route::post('products/bulk-action', [ProductController::class, 'bulkAction'])
        ->name('admin.products.bulk-action');
    Route::post('products/{id}/restore', [ProductController::class, 'restore'])
        ->name('admin.products.restore');
    Route::delete('products/{id}/force-delete', [ProductController::class, 'forceDelete'])
        ->name('admin.products.force-delete');
    Route::get('products/export', [ProductController::class, 'export'])
        ->name('admin.products.export');
    Route::post('products/import', [ProductController::class, 'import'])
        ->name('admin.products.import');

    Route::resource('products', ProductController::class)
        ->names('admin.products');

    // Inventory Management Routes
    Route::get('inventory', [InventoryController::class, 'index'])
        ->name('admin.inventory.index');
    Route::post('inventory/update', [InventoryController::class, 'update'])
        ->name('admin.inventory.update');

    // Coupons Extra Actions
    Route::post('coupons/bulk-action', [CouponController::class, 'bulkAction'])
        ->name('admin.coupons.bulk-action');
    Route::post('coupons/{id}/restore', [CouponController::class, 'restore'])
        ->name('admin.coupons.restore');
    Route::delete('coupons/{id}/force-delete', [CouponController::class, 'forceDelete'])
        ->name('admin.coupons.force-delete');
    Route::get('coupons/export', [CouponController::class, 'export'])
        ->name('admin.coupons.export');
    Route::post('coupons/import', [CouponController::class, 'import'])
        ->name('admin.coupons.import');

    Route::resource('coupons', CouponController::class)
        ->names('admin.coupons');

    // Orders Extra Actions
    Route::post('orders/bulk-action', [OrderController::class, 'bulkAction'])
        ->name('admin.orders.bulk-action');
    Route::post('orders/{id}/restore', [OrderController::class, 'restore'])
        ->name('admin.orders.restore');
    Route::delete('orders/{id}/force-delete', [OrderController::class, 'forceDelete'])
        ->name('admin.orders.force-delete');
    Route::get('orders/export', [OrderController::class, 'export'])
        ->name('admin.orders.export');

    Route::resource('orders', OrderController::class)
        ->only(['index', 'show', 'update'])
        ->names('admin.orders');

    Route::resource('roles', RoleController::class)
        ->names('admin.roles');

    Route::resource('permissions', PermissionController::class)
        ->only(['index'])
        ->names('admin.permissions');

    // Admins Custom Actions & Resource
    Route::post('admins/bulk-action', [AdminUserController::class, 'bulkAction'])
        ->name('admin.admins.bulk-action');
    Route::post('admins/{id}/restore', [AdminUserController::class, 'restore'])
        ->name('admin.admins.restore');
    Route::delete('admins/{id}/force-delete', [AdminUserController::class, 'forceDelete'])
        ->name('admin.admins.force-delete');
    Route::resource('admins', AdminUserController::class)
        ->names('admin.admins');

    // Customers Custom Actions & Resource
    Route::post('customers/bulk-action', [CustomerController::class, 'bulkAction'])
        ->name('admin.customers.bulk-action');
    Route::post('customers/{id}/restore', [CustomerController::class, 'restore'])
        ->name('admin.customers.restore');
    Route::delete('customers/{id}/force-delete', [CustomerController::class, 'forceDelete'])
        ->name('admin.customers.force-delete');
    Route::resource('customers', CustomerController::class)
        ->names('admin.customers');

    // Blogs Routes
    Route::post('blogs/bulk-action', [BlogController::class, 'bulkAction'])
        ->name('admin.blogs.bulk-action');
    Route::post('blogs/{id}/restore', [BlogController::class, 'restore'])
        ->name('admin.blogs.restore');
    Route::delete('blogs/{id}/force-delete', [BlogController::class, 'forceDelete'])
        ->name('admin.blogs.force-delete');
    Route::resource('blogs', BlogController::class)
        ->names('admin.blogs');

    // Banners Routes
    Route::post('banners/bulk-action', [BannerController::class, 'bulkAction'])
        ->name('admin.banners.bulk-action');
    Route::post('banners/{id}/restore', [BannerController::class, 'restore'])
        ->name('admin.banners.restore');
    Route::delete('banners/{id}/force-delete', [BannerController::class, 'forceDelete'])
        ->name('admin.banners.force-delete');
    Route::resource('banners', BannerController::class)
        ->names('admin.banners');

    // System Settings
    Route::get('settings', [SettingController::class, 'index'])
        ->name('admin.settings.index');
    Route::post('settings', [SettingController::class, 'update'])
        ->name('admin.settings.update');

    // Audit/Activity Logs
    Route::get('activity-logs', [ActivityLogController::class, 'index'])
        ->name('admin.activity-logs.index');

    // Global Search JSON API
    Route::get('search', [SearchController::class, 'search'])
        ->name('admin.search');

    // Admin Profile
    Route::get('profile', [ProfileController::class, 'index'])
        ->name('admin.profile.index');
    Route::put('profile', [ProfileController::class, 'update'])
        ->name('admin.profile.update');
    Route::put('profile/avatar', [ProfileController::class, 'updateAvatar'])
        ->name('admin.profile.avatar');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])
        ->name('admin.profile.password');

    // Reviews Management
    Route::get('reviews', [ReviewController::class, 'index'])->name('admin.reviews.index');
    Route::put('reviews/{id}/approve', [ReviewController::class, 'approve'])->name('admin.reviews.approve');
    Route::put('reviews/{id}/reject', [ReviewController::class, 'reject'])->name('admin.reviews.reject');
    Route::delete('reviews/{id}', [ReviewController::class, 'destroy'])->name('admin.reviews.destroy');

    // Transactions History
    Route::get('transactions', [TransactionController::class, 'index'])->name('admin.transactions.index');

    // Reports & Analytics
    Route::get('reports', [ReportController::class, 'index'])->name('admin.reports.index');

    // Support Tickets
    Route::get('support', [SupportController::class, 'index'])->name('admin.support.index');
    Route::get('support/{id}', [SupportController::class, 'show'])->name('admin.support.show');
    Route::put('support/{id}/status', [SupportController::class, 'updateStatus'])->name('admin.support.updateStatus');
    Route::delete('support/{id}', [SupportController::class, 'destroy'])->name('admin.support.destroy');

});

?>