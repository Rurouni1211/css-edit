<?php

namespace App\Http\Controllers\Artisan;

use App\Http\Controllers\Auth\OrderBaseController;

class OrderController extends OrderBaseController
{
    protected $guard = 'artisan';
}
