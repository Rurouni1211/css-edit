<?php

namespace App\Enums;

enum OrderType: string
{
    case Product = 'product'; // カスタマイズ品
    case Item = 'item';   // 完成品

    public function getLabel(): string
    {
        return match ($this) {
            self::Product => 'カスタマイズ品',
            self::Item => '完成品',
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