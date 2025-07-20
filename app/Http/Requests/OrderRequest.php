<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Rules\OrderComponent;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
        $product = Product::with('components.materials.colors.color')
            ->find($this->productId);

        return [
            'productId' => 'required|exists:products,id',
            'components' => [
                'required',
            ],
            'components.*' => [
                'required',
                'array',
                new OrderComponent($product),
            ],
        ];
    }

    public function attributes()
    {
        return [
            'productId' => '商品ID',
            'components' => 'コンポーネント',
            'components.*' => 'コンポーネント',
        ];
    }
}
