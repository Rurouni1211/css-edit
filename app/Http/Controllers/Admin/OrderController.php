<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Auth\OrderBaseController;

class OrderController extends OrderBaseController
{
    protected $guard = 'admin';
}
