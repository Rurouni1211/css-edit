<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
        ];
    }

    public function rootView(Request $request)
    {
        $route_name = $request->route()->getName();
        $is_staff = Str::startsWith($route_name, ['admin.', 'shop.', 'artisan.']);

        if($is_staff === true) { // ページによってrootView変更

            return 'staff'; // staff.blade.php

        }

        return 'store'; // store.blade.php
    }
}
