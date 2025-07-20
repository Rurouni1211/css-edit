<?php

namespace App\Rules;

use App\Enums\ComponentGroup;
use App\Enums\ComponentGroupType;
use App\Enums\MainMaterialCombinationGroup;
use App\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Arr;

class OrderComponent implements ValidationRule
{
    public function __construct(
        private Product $product,
    ) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $key = preg_replace('/^components\./', '', $attribute);
        $groupType = Product::getComponentGroupType($key);

        if($groupType === ComponentGroupType::Main->value) { // メイン

            $this->mainValidate($key, $value, $fail);

        } else if($groupType === ComponentGroupType::Sub->value) { // 共通

            $this->subValidate($key, $value, $fail); // TODO:

        } else if($groupType === ComponentGroupType::Common->value) { // 共通

            $this->commonValidate($key, $value, $fail); // TODO:

        } else if($groupType === ComponentGroupType::Logo->value) { // ロゴ

            $this->logoValidate($key, $value, $fail);

        } else {

            throw new \Exception('Invalid group type: ' . $groupType); // あるはずのキーがない

        }
    }

    private function mainValidate(string $key, mixed $value, Closure $fail): void
    {
        $product = $this->product;
        $materialCombinationGroup = MainMaterialCombinationGroup::tryFrom($key);

        // 改ざんチェック
        if(is_null($materialCombinationGroup)) {

            throw new \Exception('Invalid component: ' . $key); // あるはずのキーがない

        }

        // 素材 & 色：入力チェック
        $materialCombinationGroupLabel = $materialCombinationGroup->getLabel();
        $materialId = $value['material']['id'] ?? null;
        $colorId = $value['color']['id'] ?? null;

        if(is_null($materialId)) {

            $fail('【'. $materialCombinationGroupLabel .'】素材が選択されていません');
            return;

        } else if(is_null($colorId)) {

            $fail('【'. $materialCombinationGroupLabel .'】色が選択されていません');
            return;

        }

        // 改ざんチェック
        $mainComponentGroups = $materialCombinationGroup->getMainComponentGroups();
        $groupKeys = Arr::map($mainComponentGroups, function (ComponentGroup $component) {

            return $component->value;

        });
        $filteredComponents = $product->components
            ->filter(function ($component) use ($groupKeys) {

                return in_array($component->key, $groupKeys, true);

            });

        foreach ($filteredComponents as $filteredComponent) {

            // 素材の改ざんチェック
            $targetMaterial = $filteredComponent->materials
                ->where('material_id', $materialId)
                ->first();
            if(is_null($targetMaterial)) {

                throw new \Exception('Invalid material: ' . $key); // 存在するはずの素材IDがない

            }

            // 色の改ざんチェック
            $targetColor = $targetMaterial->colors
                ->where('material_color_id', $colorId)
                ->first();
            if(is_null($targetColor)) {

                throw new \Exception('Invalid color: ' . $key); // 存在するはずのカラーIDがない

            }

        }

    }

    private function subValidate(string $key, mixed $value, Closure $fail): void
    {
        $product = $this->product;
        $componentGroupType = ComponentGroup::from($key);
        $componentGroupTypeLabel = $componentGroupType->getLabel();

        $materialId = $value['material']['id'] ?? null;
        $colorId = $value['color']['id'] ?? null;

        if(is_null($materialId)) {

            $fail('【'. $componentGroupTypeLabel .'】素材が選択されていません');
            return;

        } else if(is_null($colorId)) {

            $fail('【'. $componentGroupTypeLabel .'】色が選択されていません');
            return;

        }

        // 改ざんチェック
        $targetComponent = $product->components
            ->where('key', $key)
            ->first();
        if(is_null($targetComponent)) {

            throw new \Exception('Invalid component key: ' . $key); // あるはずのキーがない

        }

        // 素材の改ざんチェック
        $targetMaterial = $targetComponent->materials
            ->where('material_id', $materialId)
            ->first();
        if(is_null($targetMaterial)) {

            throw new \Exception('Invalid material: ' . $key); // 存在するはずの素材IDがない

        }

        // 色の改ざんチェック
        $targetColor = $targetMaterial->colors
            ->where('material_color_id', $colorId)
            ->first();
        if(is_null($targetColor)) {

            throw new \Exception('Invalid color: ' . $key); // 存在するはずのカラーIDがない

        }
    }

    private function commonValidate(string $key, mixed $value, Closure $fail): void
    {
        $product = $this->product;
        $componentGroupType = ComponentGroup::from($key);
        $componentGroupTypeLabel = $componentGroupType->getLabel();

        $materialId = $value['material']['id'] ?? null;
        $colorId = $value['color']['id'] ?? null;

        // commonの場合は、素材選択がないので、nullを許可する
        if(is_null($colorId)) {

            $fail('【'. $componentGroupTypeLabel .'】色が選択されていません');
            return;

        }

        // 改ざんチェック
        $targetComponent = $product->components
            ->where('key', $key)
            ->first();
        if(is_null($targetComponent)) {

            throw new \Exception('Invalid component key: ' . $key); // あるはずのキーがない

        }

        // 素材の改ざんチェック
        $targetMaterial = $targetComponent->materials
            ->where('material_id', $materialId)
            ->first();
        if(is_null($targetMaterial)) {

            throw new \Exception('Invalid material: ' . $key); // 存在するはずの素材IDがない

        }

        // 色の改ざんチェック
        $targetColor = $targetMaterial->colors
            ->where('material_color_id', $colorId)
            ->first();
        if(is_null($targetColor)) {

            throw new \Exception('Invalid color: ' . $key); // 存在するはずのカラーIDがない

        }
    }

    private function logoValidate(string $key, mixed $value, Closure $fail): void
    {
        $product = $this->product;
        $targetComponent = $product->components
            ->where('key', $key)
            ->first();

        if(is_null($targetComponent)) {

            throw new \Exception('Invalid component key: ' . $key); // あるはずのキーがない

        }

        $logoEnabled = $value['logo'];

        if(! is_null($logoEnabled) && ! is_bool($logoEnabled)) {  // bool or nullじゃない場合は改ざん

            throw new \Exception('Invalid logo: ' . $key);

        } else if(is_null($logoEnabled)) {

            $componentGroup = ComponentGroup::tryFrom($key);
            $componentGroupLabel = $componentGroup->getLabel();
            $fail('【'. $componentGroupLabel .'】ロゴの有無が選択されていません');
            return;

        }
    }
}
