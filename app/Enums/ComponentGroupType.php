<?php

namespace App\Enums;

enum ComponentGroupType: string
{
    case Main = 'main';     // 金額、素材、カラー、ノーマルマップ（素材組み合わせも考慮しないといけない）
    case Sub = 'sub';       // 金額、素材、カラー、ノーマルマップ（素材組み合わせは関連しない）
    case Common = 'common'; // 金額、カラー、ノーマルマップ
    case Logo = 'logo';     // 金額のみ
    case None = 'none';

    // グループキーをつかった判定
    public static function fromComponentGroupKey(string $componentGroupKey): self
    {
        $mainGroupTypeKeys = self::getMainGroupTypeKeys();
        $subGroupTypeKeys = self::getSubTypeKeys();
        $commonTypeKeys = self::getCommonTypeKeys();
        $logoTypeKeys = self::getLogoTypeKeys();

        if (in_array($componentGroupKey, $mainGroupTypeKeys, true)) {

            return ComponentGroupType::Main;

        } elseif (in_array($componentGroupKey, $subGroupTypeKeys, true)) {

            return ComponentGroupType::Sub;

        }  elseif (in_array($componentGroupKey, $commonTypeKeys, true)) {

            return ComponentGroupType::Common;

        } elseif (in_array($componentGroupKey, $logoTypeKeys, true)) {

            return ComponentGroupType::Logo;

        }

        return ComponentGroupType::None;
    }

    // コンポーネントキーをつかった判定
    public static function fromComponentKey(string $componentKey): self
    {
        $mainTypeKeys = self::getMainTypeKeys();
        $subTypeKeys = self::getSubTypeKeys();
        $commonTypeKeys = self::getCommonTypeKeys();
        $logoTypeKeys = self::getLogoTypeKeys();

        if (in_array($componentKey, $mainTypeKeys, true)) {

            return ComponentGroupType::Main;

        } elseif (in_array($componentKey, $subTypeKeys, true)) {

            return ComponentGroupType::Sub;

        } elseif (in_array($componentKey, $commonTypeKeys, true)) {

            return ComponentGroupType::Common;

        } elseif (in_array($componentKey, $logoTypeKeys, true)) {

            return ComponentGroupType::Logo;

        }

        return ComponentGroupType::None;
    }

    // 購入者が必要な入力項目
    public function getRequiredInputs(): array
    {
        return match ($this) {
            ComponentGroupType::Main, ComponentGroupType::Sub => [
                'material_id',
                'color_id',
            ],
            ComponentGroupType::Common => [
                'color_id',
            ],
            ComponentGroupType::Logo => [
                'logo',
            ],
            default => [],
        };
    }

    // Static methods
    public static function getMainGroupTypeKeys(): array
    {
        return [
            MainMaterialCombinationGroup::Handle->value,
            MainMaterialCombinationGroup::Cover->value,
            MainMaterialCombinationGroup::Body->value,
            MainMaterialCombinationGroup::Cover_Body->value,
            MainMaterialCombinationGroup::Handle_Cover->value,
            MainMaterialCombinationGroup::Handle_Cover_Body->value,
        ];
    }

    public static function getMainTypes(): array
    {
        return MainMaterialCombinationGroup::ALL_COMPONENT_GROUPS;
    }

    public static function getMainTypeKeys(): array
    {
        return array_map(function(ComponentGroup $group) {

            return $group->value;

        }, MainMaterialCombinationGroup::ALL_COMPONENT_GROUPS);
    }

    public static function getSubTypes(): array
    {
        return [
            ComponentGroup::Common_Lining,
        ];
    }

    public static function getSubTypeKeys(): array
    {
        return array_map(function(ComponentGroup $group) {

            return $group->value;

        }, self::getSubTypes());
    }

    public static function getCommonTypes(): array
    {
        return [
            ComponentGroup::Bottom_Metal1,
            ComponentGroup::Strap_Leather1,
            ComponentGroup::Strap_Leather2,
            ComponentGroup::Transverse_section,
            ComponentGroup::Common_Metal1,
            ComponentGroup::Common_Metal2,
            ComponentGroup::Zipper_Fabric,
        ];
    }

    public static function getCommonTypeKeys(): array
    {
        return array_map(
            function (ComponentGroup $group) {

                return $group->value;

            },
            self::getCommonTypes()
        );
    }

    public static function getLogoTypes(): array
    {
        return [
            ComponentGroup::Inside_LogoMetal,
            ComponentGroup::Outside_LogoMetal,
        ];
    }

    public static function getLogoTypeKeys(): array
    {
        return array_map(
            function (ComponentGroup $group) {

                return $group->value;

            },
            self::getLogoTypes()
        );
    }

    public static function getAllTypeKeys(): array
    {
        return [
            'main_group' => self::getMainGroupTypeKeys(),
            'main' => self::getMainTypeKeys(),
            'common' => self::getCommonTypeKeys(),
            'logo' => self::getLogoTypeKeys(),
        ];
    }
}
