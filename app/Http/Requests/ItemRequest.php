<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:255',
            'item_code' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer|min:0',
            'shop_id' => 'required|integer|exists:shops,id',
            'sort_number' => 'required|integer|min:0',
            'category_id' => 'required|integer|exists:item_categories,id',
            'new_images' => 'nullable|array',
            'new_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '商品名',
            'item_code' => '商品コード',
            'description' => '商品説明',
            'price' => '価格',
            'shop_id' => '販売店舗',
            'sort_number' => '並び順',
            'category_id' => 'カテゴリ',
            'new_images' => '画像',
        ];
    }
}
