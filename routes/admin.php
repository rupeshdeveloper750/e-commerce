<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Catalog\CategoryController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;


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


    Route::resource('categories', CategoryController::class)
        ->names('admin.categories');

});

?>