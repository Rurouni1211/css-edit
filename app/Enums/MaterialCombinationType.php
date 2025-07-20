<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum MaterialCombinationType: string
{
    use EnumTrait;

    case Type_A = 'A';
    case Type_B = 'B';
    case Type_C = 'C';
    case Type_D = 'D';

    public function getLabel(): string
    {
        return match($this) {
            self::Type_A => 'TYPE-A',
            self::Type_B => 'TYPE-B',
            self::Type_C => 'TYPE-C',
            self::Type_D => 'TYPE-D',
        };
    }

    public function description(): string
    {
        $handle_label = MainMaterialCombinationGroup::Handle->getLabel();
        $lid_label = MainMaterialCombinationGroup::Cover->getLabel();
        $body_label = MainMaterialCombinationGroup::Body->getLabel();
        $lid_body_label = MainMaterialCombinationGroup::Cover_Body->getLabel();
        $handle_lid_label = MainMaterialCombinationGroup::Handle_Cover->getLabel();
        $handle_lid_body_label = MainMaterialCombinationGroup::Handle_Cover_Body->getLabel();

        return match($this) {
            self::Type_A => '（'. $handle_lid_body_label .'）すべて同時に切り替え',
            self::Type_B => '（'. $handle_label .'）（'. $lid_body_label .'）2ヶ所選択可',
            self::Type_C => '（'. $handle_lid_label .'）（'. $body_label .'）2ヶ所選択可',
            self::Type_D => '（'. $handle_label .'）（'. $lid_label .'）（'. $body_label .'）3ヶ所選択可',
        };
    }

    public function getGroups(): array
    {
        return match ($this) {
            self::Type_A => [
                [
                    'key' => MainMaterialCombinationGroup::Handle_Cover_Body->value,
                    'label' => MainMaterialCombinationGroup::Handle_Cover_Body->getLabel(),
                    'enum' => MainMaterialCombinationGroup::Handle_Cover_Body,
                ],
            ],
            self::Type_B => [
                [
                    'key' => MainMaterialCombinationGroup::Handle->value,
                    'label' => MainMaterialCombinationGroup::Handle->getLabel(),
                    'enum' => MainMaterialCombinationGroup::Handle,
                ],
                [
                    'key' => MainMaterialCombinationGroup::Cover_Body->value,
                    'label' => MainMaterialCombinationGroup::Cover_Body->getLabel(),
                    'enum' => MainMaterialCombinationGroup::Cover_Body,
                ],
            ],
            self::Type_C => [
                [
                    'key' => MainMaterialCombinationGroup::Handle_Cover->value,
                    'label' => MainMaterialCombinationGroup::Handle_Cover->getLabel(),
                    'enum' => MainMaterialCombinationGroup::Handle_Cover,
                ],
                [
                    'key' => MainMaterialCombinationGroup::Body->value,
                    'label' => MainMaterialCombinationGroup::Body->getLabel(),
                    'enum' => MainMaterialCombinationGroup::Body,
                ],
            ],
            self::Type_D => [
                [
                    'key' => MainMaterialCombinationGroup::Handle->value,
                    'label' => MainMaterialCombinationGroup::Handle->getLabel(),
                    'enum' => MainMaterialCombinationGroup::Handle,
                ],
                [
                    'key' => MainMaterialCombinationGroup::Cover->value,
                    'label' => MainMaterialCombinationGroup::Cover->getLabel(),
                    'enum' => MainMaterialCombinationGroup::Cover,
                ],
                [
                    'key' => MainMaterialCombinationGroup::Body->value,
                    'label' => MainMaterialCombinationGroup::Body->getLabel(),
                    'enum' => MainMaterialCombinationGroup::Body,
                ],
            ],
        };
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
                    'description' => $case->description(),
                ];

            }

        }

        return $items;
    }
}
