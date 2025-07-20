<?php

namespace App\Enums;

use App\Models\Admin;
use App\Models\Artisan;
use App\Models\Customer;
use App\Models\Shop;

enum UserType: string
{
    case Customer = 'customer';
    case Admin = 'admin';
    case Shop = 'shop';
    case Artisan = 'artisan';

    public function getLabel(): string
    {
        return match ($this) {
            self::Customer => 'お客様',
            self::Admin => '管理者',
            self::Shop => '店舗',
            self::Artisan => '職人',
        };
    }

    public function getModel(): string
    {
        return match ($this) {
            self::Customer => Customer::class,
            self::Admin => Admin::class,
            self::Shop => Shop::class,
            self::Artisan => Artisan::class,
        };
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getLabels(): array
    {
        $labels = [];

        foreach (self::cases() as $case) {

            $key = $case->value;
            $labels[$key] = $case->getLabel();

        }

        return $labels;
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
