<?php

namespace App\Enums;

enum ComponentGroup: string
{
    /* Main ここから */
    case Front_Leather1 = 'Front_Leather1';
    case Back_Leather1 = 'Back_Leather1';
    case Cover = 'Cover';
    case Side_Leather1 = 'Side_Leather1';
    case Bottom_Leather1 = 'Bottom_Leather1';
    case Handle_Leather = 'Handle_Leather';
    case Root_Leather = 'Root_Leather';
    case Strap_Leather1 = 'Strap_Leather1';
    case Strap_Leather2 = 'Strap_Leather2';
    case Inside_LogoLeather = 'Inside_LogoLeather';
    /* Main ここまで */

    /* Common ここから */
    case Common_Lining = 'Common_Lining';
    case Transverse_section = 'Transverse_section';
    case Common_Metal1 = 'Common_Metal1';
    case Common_Metal2 = 'Common_Metal2';
    case Zipper_Fabric = 'Zipper_Fabric';
    case Bottom_Metal1 = 'Bottom_Metal1';
    /* Common ここまで */

    /* Logo ここから */
    case Inside_LogoMetal = 'Inside_LogoMetal';
    case Outside_LogoMetal = 'Outside_LogoMetal';
    /* Logo ここまで */

    public function getLabel(): string
    {
        return match($this) {
            self::Front_Leather1 => '正面メイン部分1',
            self::Back_Leather1 => '背面',
            self::Cover => 'かぶせ',
            self::Side_Leather1 => '側面',
            self::Bottom_Leather1 => '底面',
            self::Bottom_Metal1 => '底面の金属パーツ',
            self::Handle_Leather => 'ハンドル',
            self::Root_Leather => 'ハンドルの付け根',
            self::Strap_Leather1 => 'ストラップ1',
            self::Strap_Leather2 => 'ストラップ2',
            self::Inside_LogoMetal => '内側のロゴ',
            self::Inside_LogoLeather => '内側のロゴ下の革',
            self::Outside_LogoMetal => '正面外のロゴ',
            self::Common_Lining => '内張り布',
            self::Transverse_section => 'コバ',
            self::Common_Metal1 => '金属パーツ1',
            self::Common_Metal2 => '金属パーツ2',
            self::Zipper_Fabric => 'ファスナーの布地',
        };
    }

    public static function getValues($excepts = ['none']): array
    {
        $values = array_column(self::cases(), 'value');

        return array_diff($values, $excepts);
    }

    public static function getCollection($except = ['none']): array
    {
        $items = [];

        foreach (self::cases() as $case) {

            if(! in_array($case->value, $except)) {

                $type = ComponentGroupType::fromComponentKey($case->value);

                $items[] = [
                    'name' => $case->name,
                    'value' => $case->value,
                    'group_key' => $type->value,
                    'label' => $case->getLabel(),
                ];

            }

        }

        return $items;
    }

    public static function isLogo(string $key): bool
    {
        $valid_keys = [
            self::Inside_LogoMetal->value,
            self::Outside_LogoMetal->value,
        ];

        return in_array($key, $valid_keys, true);
    }
}
