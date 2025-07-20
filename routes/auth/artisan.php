<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Artisan\DashboardController;
use App\Http\Controllers\Artisan\OrderController;
use App\Http\Controllers\Artisan\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Enums\UserType;

$guard = UserType::Artisan->value;

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

        // Order
        Route::prefix('order')->group(function(){
            Route::get('/', [OrderController::class, 'index'])->name('order.index');
            Route::get('/list', [OrderController::class, 'list'])->name('order.list');
            Route::put('/update_status/{order}', [OrderController::class, 'updateStatus'])->name('order.update_status');
        });
    });

});


