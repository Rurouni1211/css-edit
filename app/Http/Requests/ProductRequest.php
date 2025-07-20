<?php

namespace App\Http\Requests;

use App\Enums\MaterialCombinationType;
use App\Models\Material;
use App\Models\MaterialColor;
use App\Models\MaterialNormalMap;
use App\Rules\ProductComponentMaterial;
use App\Rules\ProductComponentMaterialColor;
use App\Rules\ProductComponentMaterialNormalMap;
use App\Rules\ProductComponents;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $product = $this->route('product');
        $product_id = $product->id ?? null;
        $material_ids = Material::pluck('id');
        $material_color_ids = MaterialColor::pluck('id');
        $material_normal_map_ids = MaterialNormalMap::pluck('id');

        return  [
            // 商品
            'product.category_id' => 'required|exists:product_categories,id',
            'product.material_combination_type' => [
                'required',
                'string',
                Rule::enum(MaterialCombinationType::class),
            ],
            'product.name' => 'required|string|max:255',
            'product.product_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'product_code')->ignore($product_id),
            ],
            'product.shop_id' => 'required|exists:shops,id',
            'product.sketchfab_model_key' => 'required|string|max:255',

            // 構成パーツ
            'components' => 'required|array',
            'components.*' => [
                // 標準バリデーションではタイムアウトするので、カスタムバリデーションを使う
                new ProductComponents(
                    $this,
                    $material_ids->toArray(),
                    $material_color_ids->toArray(),
                    $material_normal_map_ids->toArray(),
                ),
            ],
            'components.*.materials.*' => [
                new ProductComponentMaterial(),
            ],
            'components.*.materials.*.colors' => [
                new ProductComponentMaterialColor(),
            ],
            'components.*.materials.*.normal_maps' => [
                new ProductComponentMaterialNormalMap(),
            ]
        ];
    }

    public function attributes()
    {
        return [
            'product.shop_id' => '販売店舗',
            'product.category_id' => 'カテゴリ',
            'product.name' => '商品名',
            'product.product_code' => '商品コード',
            'product.sketchfab_model_key' => 'Sketchfab ID',
            'product.material_combination_type' => '素材組み合わせ',
            'components' => '構成パーツ',
        ];
    }
}
