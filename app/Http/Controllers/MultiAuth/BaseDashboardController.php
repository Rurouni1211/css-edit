<?php

namespace App\Http\Controllers\MultiAuth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Inertia\Inertia;

class BaseDashboardController extends Controller
{
    private $multi_auth_guard;

    public function __construct()
    {
        $this->multi_auth_guard = multi_auth_guard();
    }

    public function index()
    {
        $folder_name = Str::studly($this->multi_auth_guard);

        return Inertia::render($folder_name .'/Dashboard');
    }
}
