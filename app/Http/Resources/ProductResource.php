<?php

namespace App\Http\Resources;

use App\Enums\ComponentGroup;
use App\Enums\ComponentGroupType;
use App\Enums\MaterialCombinationType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $material_combination = [];

        if (MaterialCombinationType::has($this->material_combination_type)) {

            $material_combination_type = MaterialCombinationType::from($this->material_combination_type);

            $groups = [];
            $groups_candidates = [
                $this->getMainGroupData($material_combination_type),
                $this->getCommonGroupData(),
                $this->getLogoGroupData(),
            ];

            foreach ($groups_candidates as $groups_candidate) {

                if(count($groups_candidate) > 0) {

                    $groups = array_merge($groups, $groups_candidate);

                }

            }

            $material_combination = [
                'type' => $material_combination_type->value,
                'label' => $material_combination_type->getLabel(),
                'description' => $material_combination_type->description(),
                'groups' => $groups,
            ];

        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'product_code' => $this->product_code,
            'shop_id' => $this->shop_id,
            'sort_number' => $this->sort_number,
            'sketchfab_model_key' => $this->sketchfab_model_key,
            'material_combination_type' => $this->material_combination_type,
            'material_combination_label' => $this->material_combination_label,
            'detail_url' => $this->detail_url,
            'sketchfab_url' => $this->sketchfab_url,
            'category_id' => $this->category_id,
            'category' => ProductCategoryResource::make($this->category),
            'components' => $this->whenLoaded('components', function () {

                return ProductComponentResource::collection($this->components);

            }),
            'shop' => $this->whenLoaded('shop', function () {

                return ShopResource::make($this->shop);

            }),
            'material_combination' => $material_combination,
        ];
    }

    private function getMainGroupData($material_combination_type) // データは、getCommonGroupData()と合わせる
    {
        $data = [];
        $groups = $material_combination_type->getGroups(); // \App\Enums\MaterialCombinationType::getGroups() で取得

        foreach ($groups as $group) { // {ハンドル/ふた/ボディ}、{ハンドル}{ふた/ボディ}など

            $enum = $group['enum'];
            $component_groups = $enum->getMainComponentGroups(); // \App\Enums\MainMaterialCombinationGroup::getComponentGroups()
            $component_group_keys = Arr::map($component_groups, function ($component_group) {

                return $component_group->value;

            });
            $target_component = $this->components
                ->sortBy('id')
                ->filter(function($component){

                    return $this->hasActiveMaterials($component);

                })
                ->first(function ($component) use ($component_group_keys) {

                    /*
                        【重要】コンポーネントを構成する素材（マテリアル）は金額指定が必要なので、以下のように入力する（hasMany）
                         Front_Leather1
                         　└　シャーク：1,000円
                         　└　キャンパス：1,000円
                         　└　コードバン：1,000円
                         しかし、Front_Leather1はBodyやHandleを構成する一部であるため、管理者が登録する素材構成は他のコンポーネントと一致する必要がある。
                    　　　なぜなら、購入者のための「素材選択」は1番目のデータを取得するため。
                     */

                    return in_array($component->key, $component_group_keys, true);

                });

            // Material
            $materials = [];

            if(! is_null($target_component)) {

                $materials = $this->getTargetComponentMaterials($target_component);

            }

            $componentGroupType = ComponentGroupType::fromComponentGroupKey($group['key']);
            $data[] = [
                'key' => $group['key'],
                'group_key' => $componentGroupType->value,
                'required_inputs' => $componentGroupType->getRequiredInputs(),
                'label' => $group['label'],
                'component_groups' => $component_groups,
                'materials' => $materials,
            ];
        }

        return $data;
    }

    private function getCommonGroupData() // データは、getMainGroupData()と合わせる
    {
        $data = [];

        // TODO: 共通化する
        $component_groups = [
            ComponentGroup::Transverse_section,
            ComponentGroup::Common_Lining,
        ];

        foreach ($component_groups as  $component_group) {

            $component_group_key = $component_group->value;
            $component_group_label = $component_group->getLabel();
            $target_component = $this->components
                ->filter(function($component){

                    return $this->hasActiveMaterials($component);

                })
                ->first(function ($component) use ($component_group_key) {

                    return $component->key === $component_group_key;

                });

            if(! is_null($target_component)) {

                $materials = $this->getTargetComponentMaterials($target_component);
                $componentGroupType = ComponentGroupType::fromComponentGroupKey($component_group_key);
                $data[] = [
                    'key' => $component_group_key,
                    'group_key' => $componentGroupType->value,
                    'required_inputs' => $componentGroupType->getRequiredInputs(),
                    'label' => $component_group_label,
                    'component_groups' => [$component_group_key],
                    'materials' => $materials,
                ];

            }

        }

        return $data;
    }

    private function getLogoGroupData() // データは、getMainGroupData()と合わせる
    {
        $data = [];
        // TODO: 共通化する
        $component_groups = [
            ComponentGroup::Inside_LogoMetal,
            ComponentGroup::Outside_LogoMetal,
        ];

        foreach ($component_groups as $component_group) {

            $target_component = $this->components
                ->filter(function($component){

                    return $this->hasActiveMaterials($component);

                })
                ->first(function ($component) use ($component_group) {

                    return $component->key === $component_group->value;

                });

            if(! is_null($target_component)) {

                $componentGroupType = ComponentGroupType::fromComponentGroupKey($component_group->value);
                $data[] = [
                    'key' => $component_group->value,
                    'group_key' => $componentGroupType->value,
                    'required_inputs' => $componentGroupType->getRequiredInputs(),
                    'label' => $component_group->getLabel(),
                    'component_groups' => ['logo'],
                    'materials' => [],
                ];

            }

        }

        return $data;
    }

    // 共通メソッド
    private function hasActiveMaterials($component)
    {
        $has_active_materials = $component->materials
            ->filter(function ($material) {

                return $material->is_active === true;

            })
            ->isNotEmpty();

        return (
            $component->is_active === true &&
            $has_active_materials === true
        );
    }

    private function getTargetComponentMaterials($target_component)
    {
        return $target_component
            ->materials
            ->filter(function ($material) {

                return $material->is_active === true;

            })
            ->values()
            ->map(function ($material) {

                $active_colors = $material->colors
                    ->filter(function ($color) {

                        return $color->is_active === true;

                    })
                    ->values()
                    ->map(function ($color) {

                        return [
                            'id' => $color->color->id,
                            'name' => $color->color->name,
                            'color_code' => $color->color->color_code,
                            'texture_url' => $color->color->texture_url,
                            'small_texture_url' => $color->color->small_texture_url,
                        ];

                    })
                    ->toArray();
                $target_normal_map = $material->normal_maps
                    ->filter(function ($normal_map) {

                        return $normal_map->is_active === true;

                    })
                    ->first();

                $normal_map = [];

                if(! is_null($target_normal_map)) {

                    $normal_map = [
                        'name' => $target_normal_map->normal_map->name,
                        'url' => $target_normal_map->normal_map->url,
                    ];

                }

                $specular_map = null;

                if(! is_null($material->material->specular_map)) {

                    $specular_map = [
                        'url' => $material->material->specular_map->url,
                    ];

                }

                return [
                    'id' => $material->material->id,
                    'key' => $material->material->key,
                    'name' => $material->material->name,
                    'glossiness' => $material->material->glossiness,
                    'specular' => $material->material->specular,
                    'button_image_url' => $material->material->button_image_url,
                    'colors' => $active_colors,
                    'normal_map' => $normal_map,
                    'specular_map' => $specular_map,
                ];

            })
            ->toArray();
    }

    public function getMaterialCombinationGroups()
    {
        $data = $this->toArray(request());

        return data_get($data, 'material_combination.groups', []);
    }
}
