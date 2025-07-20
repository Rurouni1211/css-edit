<?php

namespace App\Enums;

use App\Models\Admin;
use App\Models\Artisan;
use App\Models\Customer;
use App\Models\Shop;

enum MaterialType: string
{
    case Color = 'color';
    case Size = 'image';
}
