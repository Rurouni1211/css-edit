<?php

namespace App\Enums;

enum ContactSubjectType: string
{
    case About_Product = 'about_product';
    case About_Order = 'about_order';

    public function label(): string
    {
        return match($this) {
            self::About_Product => '商品について',
            self::About_Order => '注文について',
        };
    }

    public static function getValues(): array
    {
        $data = [];
        $cases = self::cases();

        foreach ($cases as $case) {

            $data[] = $case->value;

        }

        return $data;
    }

    public static function getCollection(): array
    {
        $data = [];
        $cases = self::cases();

        foreach ($cases as $case) {

            $data[] = [
                'key' => $case->value,
                'label' => $case->label(),
            ];

        }

        return $data;
    }
}
