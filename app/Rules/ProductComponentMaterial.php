<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Arr;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;

class ProductComponentMaterial implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $is_active = (bool) $value['is_active'];

        if ($is_active === true) {

            $amount = $value['amount'];
            $has_amount = (Str::length($amount) > 0);

            // Amount
            if($has_amount === false) {

                $fail('金額を入力してください');

            } else if(! is_numeric($amount)) {

                $fail('金額は数値で入力してください');

            } else if((int) $amount < 0) {

                $fail('金額は0以上で入力してください');

            }

        }
    }
}
