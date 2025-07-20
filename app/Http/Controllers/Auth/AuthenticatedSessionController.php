<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\MultiAuthLoginRequest;
use App\Models\Admin;
use App\Models\Artisan;
use App\Models\Customer;
use App\Models\Shop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    private $multi_auth_guard;

    public function __construct()
    {
        $this->multi_auth_guard = multi_auth_guard();
    }

    /**
     * Display the login view.
     */
    public function create(): Response
    {
        $guard = $this->multi_auth_guard;
        $userType = UserType::tryFrom($guard);
        $loginTitle = $userType ? $userType->getLabel() . 'ログイン' : 'ログイン';
        
        $params = [
            'status' => session('status'),
            'loginTitle' => $loginTitle,
        ];

        if(app()->environment('local')) {
            $params['quickLoginUsers'] = $this->getQuickLoginUsers();
        }

        return Inertia::render('Auth/Login', $params);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(MultiAuthLoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $redirect_url = route($this->multi_auth_guard .'.dashboard');

        return redirect()->intended($redirect_url);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $guard = $this->multi_auth_guard;
        Auth::guard($guard)->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // ガードに応じたログインページにリダイレクト
        switch ($guard) {
            case UserType::Admin->value:
                return to_route('admin.login');
            case UserType::Shop->value:
                return to_route('shop.login');
            case UserType::Artisan->value:
                return to_route('artisan.login');
            default:
                return redirect('/');
        }
    }

    // 注意！！！： 開発用のみ
    private function getQuickLoginUsers()
    {
        if(app()->environment('local')) {

            $query = null;

            if($this->multi_auth_guard === UserType::Customer->value) {

                $query = Customer::query();

            } else if($this->multi_auth_guard === UserType::Admin->value) {

                $query = Admin::query();

            } else if($this->multi_auth_guard === UserType::Shop->value) {

                $query = Shop::query();

            } else if($this->multi_auth_guard === UserType::Artisan->value) {

                $query = Artisan::query();

            }

            return $query->limit(7)->get();

        }

        return [];
    }
}
