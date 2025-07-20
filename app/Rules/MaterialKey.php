<?php

namespace App\Rules;

use App\Models\Material;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class MaterialKey implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $materialKeys = Material::getMaterialButtonKeys();

        $isValidKey = false;
        foreach ($materialKeys as $materialKey) {
            if (Str::startsWith($value, $materialKey)) {
                $isValidKey = true;
                break;
            }
        }
        
        if (! $isValidKey) {
            $availableKeys = [];
            foreach ($materialKeys as $materialKey) {
                $availableKeys[] = '「'. $materialKey . '」';
            }

            $orderedAvailableKeys = collect($availableKeys)
                ->sort()  // アルファベット順にソート
                ->values()
                ->all();

            $fail('選択された :attribute は <a href="#" class="text-blue-500 underline" onclick="window.showMaterialKeyList(); event.preventDefault();"><small>有効なキー</small></a> いずれかで始まる必要があります。');
        }
    }
}
