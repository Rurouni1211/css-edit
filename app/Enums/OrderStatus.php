<?php

namespace App\Enums;

use App\Models\Admin;
use App\Models\Artisan;
use App\Models\Customer;
use App\Models\Shop;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Completed = 'completed';
    case Dispatched = 'dispatched';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';
    case None = 'none';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => '準備中',
            self::Processing => '手掛け中',
            self::Completed => '完成',
            self::Dispatched => '発送済み',
            self::Delivered => '配達済み',
            self::Cancelled => 'キャンセル',
            self::None => 'なし',
        };
    }

    public static function getValues($excepts = ['none']): array
    {
        $values = array_column(self::cases(), 'value');

        return array_diff($values, $excepts);
    }

    public static function getLabels($excepts = ['none']): array
    {
        $labels = [];

        foreach (self::cases() as $case) {

            if(! in_array($case->value, $excepts)) {

                $key = $case->value;
                $labels[$key] = $case->getLabel();

            }

        }

        return $labels;
    }

    public static function getCollection($except = ['none']): array
    {
        $items = [];

        foreach (self::cases() as $case) {

            if(! in_array($case->value, $except)) {

                $items[] = [
                    'name' => $case->name,
                    'value' => $case->value,
                    'label' => $case->getLabel(),
                ];

            }

        }

        return $items;
    }
}
