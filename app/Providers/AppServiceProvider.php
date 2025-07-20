<?php

namespace App\Providers;

use App\Enums\OrderStatus;
use App\Enums\UserType;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->initInertiaData();
        $this->initAuthPasswordDefaults();
    }

    private function initInertiaData()
    {
        $multi_auth_guard = multi_auth_guard();
        $user_types = UserType::getCollection();
        $user_type = UserType::tryFrom($multi_auth_guard);
        $multi_auth_guard_name = ($user_type) ? $user_type->getLabel() : null;
        $order_statuses = OrderStatus::getCollection();

        Inertia::share([
            'appName' => config('app.name'),
            'multiAuthGuard' => $multi_auth_guard,
            'multiAuthGuardName' => $multi_auth_guard_name,
            'userTypes' => $user_types,
            'orderStatuses' => $order_statuses,
        ]);
    }

    private function initAuthPasswordDefaults()
    {
        Password::defaults(function () {
            return Password::min(8)
                ->letters()
                ->numbers()
                ->uncompromised();
        });
    }
}
