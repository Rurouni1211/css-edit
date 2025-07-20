<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Auth\OrderBaseController;

class OrderController extends OrderBaseController
{
    protected $guard = 'shop';
}
