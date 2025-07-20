<?php

namespace App\Enums;

use App\Models\Admin;
use App\Models\Artisan;
use App\Models\Customer;
use App\Models\Shop;

enum MerchandiseType: string
{
    case Product = 'product';
    case Item = 'item';

    public function getLabel(): string
    {
        return match ($this) {
            self::Product => 'オーダーメイド品',
            self::Item => '完成品',
        };
    }

    public static function getCollection(): array
    {
        $items = [];

        foreach (self::cases() as $case) {

            $items[] = [
                'name' => $case->name,
                'value' => $case->value,
                'label' => $case->getLabel(),
            ];

        }

        return $items;
    }
}
