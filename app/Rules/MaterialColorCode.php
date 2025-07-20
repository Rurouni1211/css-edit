<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class MaterialColorCode implements ValidationRule
{
    /**
     * Indicates whether the rule should be implicit.
     *
     * @var bool
     */
    public $implicit = true;

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $pattern = '|colors\.([0-9]+)\.color_code|';
        preg_match($pattern, $attribute, $matches);

        if(count($matches) === 0) {

            $fail("The $attribute is invalid.");

        }

        $index = (int) $matches[1];

        if($index === -1) {

            $fail("The $attribute is invalid.");

        }

        $request = request();

        $has_file = (
            $request->hasFile('colors.'. $index .'.texture_file') || // 新ファイルあり
            $request->filled('colors.'. $index .'.texture_filename') // 既存ファイルあり
        );
        $has_color_code = Str::length($value) > 0;

        if($has_color_code === false && $has_file === false) { // ファイルがないのに色コードもない場合

            $fail('画像がない場合は必須入力です');

        } else if($has_color_code === true) {

            $color_code_pattern = '|^#[0-9a-fA-F]{6}$|';

            if(preg_match($color_code_pattern, $value) === 0) {

                $fail('#から始まる6桁の16進数で入力してください。');

            }

        }
    }
}
