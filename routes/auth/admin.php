<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\ArtisanController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DataListController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\ItemCategoryController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\SketchfabModelController;
use Illuminate\Support\Facades\Route;
use App\Enums\UserType;

$guard = UserType::Admin->value;

Route::prefix($guard)->name($guard .'.')->group(function() use ($guard) {

    $guest_middleware = 'guest:'. $guard;
    $auth_middleware = 'auth:'. $guard;

    Route::middleware($guest_middleware)->group(function () {
        Route::get('login', [AuthenticatedSessionController::class, 'create'])
            ->name('login');

        Route::post('login', [AuthenticatedSessionController::class, 'store'])
            ->name('login.post');

        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
            ->name('password.request');

        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
            ->name('password.email');

        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
            ->name('password.reset');

        Route::post('reset-password', [NewPasswordController::class, 'store'])
            ->name('password.store');
    });

    Route::middleware($auth_middleware)->group(function () {
        Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
            ->name('password.confirm');

        Route::post('confirm-password', [ConfirmablePasswordController::class, 'store'])
            ->name('password.confirm.store');

        Route::put('password', [PasswordController::class, 'update'])
            ->name('password.update');

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');

        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // DataList
        Route::get('data_list', [DataListController::class, 'index'])->name('data_list.index');

        // Product（オーダーメイド品）
        Route::prefix('product')->group(function() {
            Route::get('/', [ProductController::class, 'index'])->name('product.index');
            Route::get('list', [ProductController::class, 'list'])->name('product.list');
            Route::get('input/{product?}', [ProductController::class, 'input'])->name('product.input');
            Route::post('/', [ProductController::class, 'store'])->name('product.store');
            Route::post('/extract_unique_id', [ProductController::class, 'extractUniqueId'])->name('product.extract_unique_id');
            Route::post('/duplicate/{product}', [ProductController::class, 'duplicate'])->name('product.duplicate');
            Route::put('/{product}', [ProductController::class, 'update'])->name('product.update');
            Route::delete('/{product}', [ProductController::class, 'destroy'])->name('product.destroy');
        });

        // Product category
        Route::prefix('product_category')->group(function() {
            Route::get('/', [ProductCategoryController::class, 'index'])->name('product_category.index');
            Route::get('list', [ProductCategoryController::class, 'list'])->name('product_category.list');
            Route::get('input/{product_category?}', [ProductCategoryController::class, 'input'])->name('product_category.input');
            Route::post('/', [ProductCategoryController::class, 'store'])->name('product_category.store');
            Route::put('/{product_category}', [ProductCategoryController::class, 'update'])->name('product_category.update');
            Route::delete('/{product_category}', [ProductCategoryController::class, 'destroy'])->name('product_category.destroy');
        });

        // Item（完成品）
        Route::prefix('item')->group(function() {
            Route::get('/', [ItemController::class, 'index'])->name('item.index');
            Route::get('list', [ItemController::class, 'list'])->name('item.list');
            Route::get('input/{item?}', [ItemController::class, 'input'])->name('item.input');
            Route::post('/', [ItemController::class, 'store'])->name('item.store');
            Route::post('/duplicate/{item}', [ItemController::class, 'duplicate'])->name('item.duplicate');
            Route::put('/{item}', [ItemController::class, 'update'])->name('item.update');
            Route::delete('/{item}', [ItemController::class, 'destroy'])->name('item.destroy');
        });

        // Item category
        Route::prefix('item_category')->group(function() {
            Route::get('/', [ItemCategoryController::class, 'index'])->name('item_category.index');
            Route::get('list', [ItemCategoryController::class, 'list'])->name('item_category.list');
            Route::get('input/{item_category?}', [ItemCategoryController::class, 'input'])->name('item_category.input');
            Route::post('/', [ItemCategoryController::class, 'store'])->name('item_category.store');
            Route::put('/{item_category}', [ItemCategoryController::class, 'update'])->name('item_category.update');
            Route::delete('/{item_category}', [ItemCategoryController::class, 'destroy'])->name('item_category.destroy');
        });

        // Sketchfab model
        Route::prefix('sketchfab_model')->group(function(){
            Route::get('list', [SketchfabModelController::class, 'list'])->name('sketchfab_model.list');
        });

        // Material
        Route::prefix('material')->group(function(){
            Route::get('/', [MaterialController::class, 'index'])->name('material.index');
            Route::get('list', [MaterialController::class, 'list'])->name('material.list');
            Route::get('input/{material?}', [MaterialController::class, 'input'])->name('material.input');
            Route::post('/', [MaterialController::class, 'store'])->name('material.store');
            Route::put('/{material}', [MaterialController::class, 'update'])->name('material.update');
            Route::delete('/{material}', [MaterialController::class, 'destroy'])->name('material.destroy');
        });

        // Shop
        Route::prefix('shop')->group(function(){
            Route::get('/', [ShopController::class, 'index'])->name('shop.index');
            Route::get('list', [ShopController::class, 'list'])->name('shop.list');
            Route::get('input/{shop?}', [ShopController::class, 'input'])->name('shop.input');
            Route::post('/', [ShopController::class, 'store'])->name('shop.store');
            Route::put('/{shop}', [ShopController::class, 'update'])->name('shop.update');
            Route::delete('/{shop}', [ShopController::class, 'destroy'])->name('shop.destroy');
        });

        // Artisan
        Route::prefix('artisan')->group(function(){
            Route::get('/', [ArtisanController::class, 'index'])->name('artisan.index');
            Route::get('list', [ArtisanController::class, 'list'])->name('artisan.list');
            Route::get('input/{artisan?}', [ArtisanController::class, 'input'])->name('artisan.input');
            Route::post('/', [ArtisanController::class, 'store'])->name('artisan.store');
            Route::put('/{artisan}', [ArtisanController::class, 'update'])->name('artisan.update');
            Route::delete('/{artisan}', [ArtisanController::class, 'destroy'])->name('artisan.destroy');
        });

        // Order
        Route::prefix('order')->group(function(){
            Route::get('/', [OrderController::class, 'index'])->name('order.index');
            Route::get('/list', [OrderController::class, 'list'])->name('order.list');
            Route::put('/update_status/{order}', [OrderController::class, 'updateStatus'])->name('order.update_status');
        });
    });

});


