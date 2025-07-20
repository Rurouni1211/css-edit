<?php

namespace App\Rules;

use App\Enums\ComponentGroup;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProductComponentMaterialColor implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $pattern = '/components\.(\d+)\.materials\.(\d+)\.colors/';

        if(preg_match($pattern, $attribute, $matches)) {

            $component_index = $matches[1];
            $material_index = $matches[2];

            $component_key = request('components.' . $component_index . '.key');
            $is_logo_key = (
                $component_key === ComponentGroup::Inside_LogoMetal->value ||
                $component_key === ComponentGroup::Outside_LogoMetal->value
            );

            if($is_logo_key === true) {

                return;

            }

            $parent_is_active = request('components.' . $component_index . '.materials.' . $material_index . '.is_active');

            if($parent_is_active === false) {

                return;

            }

            $has_is_active = collect($value)->some(function ($item) {

                return $item['is_active'] === true;

            });

            if($has_is_active === false) {

                $fail('カラーを選択してください');

            }


            return;

        }

        throw new \Exception('Invalid attribute: ' . $attribute);
    }
}
