<?php

namespace App\Rules;

use App\Enums\ComponentGroup;
use App\Enums\ComponentGroupType;
use App\Enums\MaterialCombinationType;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class ProductComponents implements ValidationRule
{
    public function __construct(
        private ProductRequest $request,
        private $material_ids,
        private $material_color_ids,
        private $material_normal_map_ids
    ){}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $key = $value['key'];

        if(Str::length($key) === 0 || is_string($key) === false) {

            $fail('The key must be a string.');

        }

        $is_active = $value['is_active'];

        if(is_bool($is_active) === false) {

            $fail('The is_active must be a boolean.');

        }

        // 素材
        foreach ($value['materials'] as $material) {

            $material_id = $material['material_id'];
            $is_active = $material['is_active'];

            if(! ComponentGroup::isLogo($key) && ! in_array($material_id, $this->material_ids, true)) {

                $fail('The material_id must be a valid material id.');

            } else if(is_bool($is_active) === false) {

                $fail('The is_active must be a boolean.');

            }

            // カラー
            foreach ($material['colors'] as $color) {

                $material_color_id = $color['material_color_id'];
                $is_active = $color['is_active'];

                if(! in_array($material_color_id, $this->material_color_ids, true)) {

                    $fail('The material_color_id must be a valid material color id.');

                } else if(is_bool($is_active) === false) {

                    $fail('The is_active must be a boolean.');

                }

            }

            // ノーマルマップ
            foreach ($material['normal_maps'] as $normal_map) {

                $material_normal_map_id = $normal_map['material_normal_map_id'];
                $is_active = $normal_map['is_active'];

                if(! in_array($material_normal_map_id, $this->material_normal_map_ids, true)) {

                    $fail('The material_normal_map_id must be a valid material normal map id.');

                } else if(is_bool($is_active) === false) {

                    $fail('The is_active must be a boolean.');

                }

            }

        }

        $this->validateSameStructure($key, $fail);
    }

    // 素材の構成が同じであるべき他のコンポーネントを取得する
    private function validateSameStructure(string $key, Closure $fail): void
    {
        // 素材組み合わせ
        $materialCombinationType = MaterialCombinationType::tryFrom(
            $this->request->input('product.material_combination_type')
        );

        if(is_null($materialCombinationType)) {

            $fail('素材組み合わせは必須です。'); // 他でチェックするが、念のため
            return;

        }

        $checkingKeys = []; // ここに入ってくるキーをもつ素材構成をチェックする
        $combinationGroups = $materialCombinationType->getGroups();

        foreach ($combinationGroups as $combinationGroup) {

            $combinationGroupEnum = $combinationGroup['enum'];
            $mainComponentGroups = $combinationGroupEnum->getMainComponentGroups();
            $mainComponentGroupsCollection = collect($mainComponentGroups);
            $isMainComponentGroup = $mainComponentGroupsCollection
                ->contains(function ($component) use ($key) {

                    return $key === $component->value;

                });

            if($isMainComponentGroup === true) {

                $checkingKeys = $mainComponentGroupsCollection->filter(function ($mainComponentGroup) use ($key) {

                    return $key !== $mainComponentGroup->value;

                })->map(function ($mainComponentGroup) {

                    return $mainComponentGroup->value;

                })->toArray();
                break;

            }

        }

        if(count($checkingKeys) > 0) {

            $baseMaterialIdString = $this->getActiveMaterialIdString($key);

            // 他のコンポーネントの素材構成をチェックする
            foreach ($checkingKeys as $checkingKey) {

                $checkingMaterialIdString = $this->getActiveMaterialIdString($checkingKey);

                if(Str::length($baseMaterialIdString) > 0 &&
                    Str::length($checkingMaterialIdString) > 0 &&
                    $baseMaterialIdString !== $checkingMaterialIdString) { // チェックした素材の構成が異なる

                    $checkingComponentGroup = ComponentGroup::tryFrom($checkingKey);
                    $message = $checkingComponentGroup->getLabel() . '（'. $checkingKey.'）との素材構成を一致させてください。';
                    $fail($message);
                    return;

                }

            }

        }
    }

    // 選択された素材構成を文字列にしたもの
    private function getActiveMaterialIdString (string $key): string
    {
        $components = collect($this->request->input('components'));
        $targetComponent = $components->firstWhere('key', $key);

        if(! is_null($targetComponent)) {

            return collect($targetComponent['materials'])
                ->filter(function ($material) {

                    return $material['is_active'] === true;

                })->map(function ($material) {

                    return $material['material_id'];

                })->implode('-');

        }

        return '';
    }
}
