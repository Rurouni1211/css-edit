<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ComponentGroup;
use App\Enums\ComponentGroupType;
use App\Enums\MaterialCombinationType;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\MaterialResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ShopResource;
use App\Models\Material;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductComponent;
use App\Models\ProductComponentMaterial;
use App\Models\ProductComponentMaterialColor;
use App\Models\ProductComponentMaterialNormalMap;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Product/Index');
    }

    public function list(Request $request)
    {
        $products = Product::query()
            ->with('category', 'components', 'shop')
            ->whereSearch($request)
            ->orderBy('id', 'desc')
            ->paginate(15);

        return ProductResource::collection($products);
    }

    public function input(?Product $product)
    {
        $product->load(
            'components.materials.material',
            'components.materials.colors.color',
            'components.materials.normal_maps.normal_map',
        );

        $materials = Material::query()
            ->with('colors', 'normal_maps')
            ->get();
        $shops = Shop::get();
        $product_categories = ProductCategory::get();

        ProductResource::withoutWrapping();
        ShopResource::withoutWrapping();

        return Inertia::render('Admin/Product/Input', [
            'product' => ProductResource::make($product),
            'shops' => ShopResource::collection($shops),
            'productCategories' => $product_categories,
            'materials' => MaterialResource::collection($materials),
            'componentGroups' => ComponentGroup::getCollection(),
            'materialCombinationTypes' => MaterialCombinationType::getCollection(),
            'allComponentGroupTypeKeys' => ComponentGroupType::getAllTypeKeys(),
        ]);
    }

    public function store(ProductRequest $request)
    {
        $product = new Product();

        return $this->save($request, $product);
    }

    public function update(ProductRequest $request, Product $product)
    {
        return $this->save($request, $product);
    }

    private function save(Request $request, Product $product)
    {
        $result = false;
        $components = $request->components;

        // 削除するクラス
        $deleting_classes = [
            ProductComponentMaterialColor::class,
            ProductComponentMaterialNormalMap::class,
            ProductComponentMaterial::class,
            ProductComponent::class,
        ];

        DB::beginTransaction();

        try {

            // 削除
            foreach ($deleting_classes as $deleting_class) {

                (new $deleting_class())
                    ->where('product_id', $product->id)
                    ->delete();

            }

            // 商品
            $product->category_id = $request->input('product.category_id');
            $product->name = $request->input('product.name');
            $product->product_code = $request->input('product.product_code');
            $product->shop_id = $request->input('product.shop_id');
            $product->sort_number = $request->input('product.sort_number');
            $product->sketchfab_model_key = $request->input('product.sketchfab_model_key');
            $product->material_combination_type = $request->input('product.material_combination_type');
            $product->save();

            // 構成パーツ
            foreach ($components as $component) {

                $product_component = new ProductComponent();
                $product_component->product_id = $product->id;
                $product_component->key = $component['key'];
                $product_component->is_active = $component['is_active'];
                $product_component->save();

                $is_logo_key = (
                    $product_component->key === ComponentGroup::Inside_LogoMetal->value ||
                    $product_component->key === ComponentGroup::Outside_LogoMetal->value
                );

                if($is_logo_key === true) { // ロゴ

                    $product_component_material = new ProductComponentMaterial();
                    $product_component_material->product_id = $product->id;
                    $product_component_material->product_component_id = $product_component->id;
                    $product_component_material->material_id = null; // ロゴは素材を持たない
                    $product_component_material->amount = $component['materials'][0]['amount'];
                    $product_component_material->is_active = $component['is_active'];
                    $product_component_material->save();

                } else {

                    // 素材
                    foreach ($component['materials'] as $material) {

                        $product_component_material = new ProductComponentMaterial();
                        $product_component_material->product_id = $product->id;
                        $product_component_material->product_component_id = $product_component->id;
                        $product_component_material->material_id = $material['material_id'];
                        $product_component_material->amount = $material['amount'];
                        $product_component_material->is_active = $material['is_active'];
                        $product_component_material->save();

                        // カラー
                        foreach ($material['colors'] as $color) {

                            $product_component_material_color = new ProductComponentMaterialColor();
                            $product_component_material_color->product_id = $product->id;
                            $product_component_material_color->product_component_material_id = $product_component_material->id;
                            $product_component_material_color->material_color_id = $color['material_color_id'];
                            $product_component_material_color->is_active = $color['is_active'];
                            $product_component_material_color->save();

                        }

                        // ノーマルマップ
                        foreach ($material['normal_maps'] as $normal_map) {

                            $product_component_material_normal_map = new ProductComponentMaterialNormalMap();
                            $product_component_material_normal_map->product_id = $product->id;
                            $product_component_material_normal_map->product_component_material_id = $product_component_material->id;
                            $product_component_material_normal_map->material_normal_map_id = $normal_map['material_normal_map_id'];
                            $product_component_material_normal_map->is_active = $normal_map['is_active'];
                            $product_component_material_normal_map->save();

                        }

                    }

                }

            }

            DB::commit();
            $result = true;

        } catch (\Exception $e) {

            DB::rollBack();
            logger()->error($e);

        }

        return [
            'result' => $result,
        ];
    }

    public function destroy(Product $product)
    {
        $result = false;

        DB::beginTransaction();

        try {

            $product->components()->delete();
            $product->delete();
            DB::commit();
            $result = true;

        } catch (\Exception $e) {

            DB::rollBack();
            logger()->error($e);

        }

        return [
            'result' => $result,
        ];
    }

    public function extractUniqueId(Request $request)
    {
        $url = $request->url;
        $pattern = '|https://sketchfab.com/3d-models/[0-9a-zA-Z]+|';

        if(preg_match($pattern, $url, $matches)) {

            $html = file_get_contents($url);
            $pattern = '|<meta property="og:image" content="https://media.sketchfab.com/models/([0-9a-zA-Z]+)|';

            if(preg_match($pattern, $html, $matches)) {

                return [
                    'unique_id' => $matches[1],
                ];

            }

        }

        return '';
    }

    public function duplicate(Product $product)
    {
        $result = false;

        DB::beginTransaction();

        try {

            $new_product = $product->replicate();
            $new_product->name = $product->name . ' (コピー)';
            $new_product->product_code = $product->product_code . date('YmdHis');
            $new_product->save();

            foreach ($product->components as $component) {

                $new_component = $component->replicate();
                $new_component->product_id = $new_product->id;
                $new_component->save();

                foreach ($component->materials as $material) {

                    $new_material = $material->replicate();
                    $new_material->product_id = $new_product->id;
                    $new_material->product_component_id = $new_component->id;
                    $new_material->save();

                    foreach ($material->colors as $color) {

                        $new_color = $color->replicate();
                        $new_color->product_id = $new_product->id;
                        $new_color->product_component_material_id = $new_material->id;
                        $new_color->save();

                    }

                    foreach ($material->normal_maps as $normal_map) {

                        $new_normal_map = $normal_map->replicate();
                        $new_normal_map->product_id = $new_product->id;
                        $new_normal_map->product_component_material_id = $new_material->id;
                        $new_normal_map->save();

                    }

                }

            }

            DB::commit();
            $result = true;

        } catch (\Exception $e) {

            DB::rollBack();
            logger()->error($e);

        }

        return [
            'result' => $result,
        ];
    }
}
