<?php

namespace Database\Seeders;

use App\Enums\ComponentGroup;
use App\Enums\MaterialCombinationType;
use App\Models\Material;
use App\Models\MaterialColor;
use App\Models\MaterialNormalMap;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductComponent;
use App\Models\ProductComponentMaterial;
use App\Models\ProductComponentMaterialColor;
use App\Models\ProductComponentMaterialNormalMap;
use App\Models\Shop;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ProductCategory::get();
        $materials = Material::get();
        $material_colors = MaterialColor::get();
        $shops = Shop::get();
        $material_combination_types = MaterialCombinationType::getValues();
        $models = $this->getSketchfabModels();

        $random_materials = $materials->random(4); // バリデーションにひっかかるので、固定したランダム素材をつかう

        foreach ($models as $index => $sketchfab_model) {

            $sketchfab_model_key = $sketchfab_model['key'];
            $category = $categories->random();

            // 商品
            $product = new Product();
            $product->category_id = $category->id;
            $product->name = '商品' . ($index + 1);
            $product->product_code = 'product_' . Str::random(10);
            $product->shop_id = $shops->random()->id;
            $product->sort_number = Arr::random([1, 2, 3, 50, 100]);
            $product->sketchfab_model_key = $sketchfab_model_key;
            $product->material_combination_type = Arr::random($material_combination_types);
            $product->save();

            // 構成パーツ
            foreach ($sketchfab_model['component_keys'] as $component_key) {

                $product_component = new ProductComponent();
                $product_component->product_id = $product->id;
                $product_component->key = $component_key;
                $product_component->is_active = true;
                $product_component->save();

                $is_logo_key = (
                    $component_key === ComponentGroup::Inside_LogoMetal->value ||
                    $component_key === ComponentGroup::Outside_LogoMetal->value
                );

                if($is_logo_key === true) { // ロゴ

                    $product_component_material = new ProductComponentMaterial();
                    $product_component_material->product_id = $product->id;
                    $product_component_material->product_component_id = $product_component->id;
                    $product_component_material->material_id = null; // ロゴは素材を持たない
                    $product_component_material->amount = 1000 * rand(1, 5);
                    $product_component_material->is_active = true;
                    $product_component_material->save();

                } else { // 素材

                    foreach ($random_materials as $material) {

                        $product_component_material = new ProductComponentMaterial();
                        $product_component_material->product_id = $product->id;
                        $product_component_material->product_component_id = $product_component->id;
                        $product_component_material->material_id = $material->id;
                        $product_component_material->amount = 1000 * rand(1, 20);
                        $product_component_material->is_active = true;
                        $product_component_material->save();

                        // カラー
                        $material_colors
                            ->filter(function ($material_color) use ($material) {

                                return (int) $material_color->material_id === (int) $material->id;

                            })
                            ->each(function ($material_color) use ($product, $product_component_material) {

                                $color = new ProductComponentMaterialColor();
                                $color->product_id = $product->id;
                                $color->product_component_material_id = $product_component_material->id;
                                $color->material_color_id = $material_color->id;
                                $color->is_active = (Arr::random([1, 2]) === 1);
                                $color->save();

                            });

                        // ノーマルマップ（複数あるうちの1つだけチェック）
                        $normal_map = new ProductComponentMaterialNormalMap();
                        $normal_map->product_id = $product->id;
                        $normal_map->product_component_material_id = $product_component_material->id;
                        $normal_map->material_normal_map_id = $material->normal_maps->random()->id;
                        $normal_map->is_active = true;
                        $normal_map->save();

                    }

                }

            }

        }
    }

    private function getSketchfabModels()
    {
        return [
            [
                'key' => '1a69e80820184056880f23a2d27980ef',
                'component_keys' => [
                    ComponentGroup::Front_Leather1->value,
                    ComponentGroup::Back_Leather1->value,
                    ComponentGroup::Cover->value,
                    ComponentGroup::Side_Leather1->value,
                    ComponentGroup::Bottom_Leather1->value,
                    ComponentGroup::Handle_Leather->value,
                    ComponentGroup::Root_Leather->value,
                    ComponentGroup::Inside_LogoLeather->value,
                    ComponentGroup::Common_Lining->value,
                    ComponentGroup::Transverse_section->value,
                    ComponentGroup::Common_Metal1->value,
                    ComponentGroup::Bottom_Metal1->value,
                    ComponentGroup::Inside_LogoMetal->value,
                    ComponentGroup::Outside_LogoMetal->value,
                ],
            ],
        ];
    }
}
