<?php

use App\Enums\UserType;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OthersController;
use App\Http\Controllers\Store\ProductController;
use App\Http\Controllers\Store\ProductImageController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Top');
});

// Store
Route::prefix('store')->group(function() {
    // Products
    Route::prefix('product')->controller(ProductController::class)->group(function() {
        Route::get('group_type', 'groupType')->name('store.product.group_type');
        Route::get('/', 'index')->name('store.product.index');
        Route::get('{product}', 'show')->name('store.product.show');
        Route::post('amounts', 'amounts')->name('store.product.amounts');
        Route::post('/', 'store')->name('store.product.store');
        Route::get('complete/{orderUniqueId}', 'complete')->name('store.product.complete');
    });
    // Product Images
    Route::prefix('product-image')->controller(ProductImageController::class)->group(function() {
        Route::prefix('texture')->group(function() {
            Route::get('small/{material_id}/{filename}', 'textureSmallImage')
                ->name('store.product_image.texture_small_image');
        });
    });
});

// Others
Route::controller(OthersController::class)->group(function() {
    Route::get('concept', 'concept')->name('others.concept');
    Route::get('terms_and_conditions', 'termsAndConditions')->name('others.terms_and_conditions');
    Route::get('privacy_policy', 'privacyPolicy')->name('others.privacy_policy');
    Route::get('store_details', 'storeDetails')->name('others.store_details');
    Route::get('about_me', 'aboutMe')->name('others.about_me');
    Route::get('faq', 'faq')->name('others.faq');
});

// Contact
// Route::prefix('contact')->controller(ContactController::class)->group(function() {
//     Route::get('create', 'create')->name('contact.create');
//     Route::post('/', 'store')->name('contact.store');
// });

// Login top page
Route::get('/login', function () {
    return Inertia::render('Welcome', [
        'userTypes' => UserType::getCollection(),
    ]);
});

require __DIR__.'/auth/customer.php';
require __DIR__.'/auth/admin.php';
require __DIR__.'/auth/shop.php';
require __DIR__.'/auth/artisan.php';

