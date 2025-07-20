<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum MainMaterialCombinationGroup: string
{
    use EnumTrait;

    case Handle = 'Handle';
    case Cover = 'Cover';
    case Body = 'Body';
    case Cover_Body = 'Cover_Body';
    case Handle_Cover = 'Handle_Cover';
    case Handle_Cover_Body = 'Handle_Cover_Body';

    // 定数
    const HANDLE_COMPONENT_GROUPS = [
        ComponentGroup::Handle_Leather,
        ComponentGroup::Root_Leather,
    ];
    const COVER_COMPONENT_GROUPS = [
        ComponentGroup::Cover,
    ];
    const BODY_COMPONENT_GROUPS = [
        ComponentGroup::Front_Leather1,
        ComponentGroup::Back_Leather1,
        ComponentGroup::Side_Leather1,
        ComponentGroup::Bottom_Leather1,
        ComponentGroup::Inside_LogoLeather,
    ];
    const ALL_COMPONENT_GROUPS = [
        ...self::HANDLE_COMPONENT_GROUPS,
        ...self::COVER_COMPONENT_GROUPS,
        ...self::BODY_COMPONENT_GROUPS,
    ];

    public function getLabel(): string
    {
        return match($this) {
            self::Handle => 'ハンドル',
            self::Cover => 'ふた',
            self::Body => 'ボディ',
            self::Cover_Body => 'ふた / ボディ',
            self::Handle_Cover => 'ハンドル / ふた',
            self::Handle_Cover_Body => 'ハンドル / ふた / ボディ',
        };
    }

    public function getMainComponentGroups()
    {
        return match ($this) {
            self::Handle => self::HANDLE_COMPONENT_GROUPS,
            self::Cover => self::COVER_COMPONENT_GROUPS,
            self::Body => self::BODY_COMPONENT_GROUPS,
            self::Cover_Body => [
                ...self::COVER_COMPONENT_GROUPS,
                ...self::BODY_COMPONENT_GROUPS
            ],
            self::Handle_Cover => [
                ...self::HANDLE_COMPONENT_GROUPS,
                ...self::COVER_COMPONENT_GROUPS
            ],
            self::Handle_Cover_Body => [
                ...self::HANDLE_COMPONENT_GROUPS,
                ...self::COVER_COMPONENT_GROUPS,
                ...self::BODY_COMPONENT_GROUPS
            ],
        };
    }

    public static function getCollection(): array
    {
        $items = [];

        foreach (self::cases() as $case) {

            $items[] = [
                'key' => $case->value,
                'label' => $case->getLabel(),
                'component_group_keys' => $case->getMainComponentGroups(),
            ];

        }

        return $items;
    }
}
