<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemCategoryRequest extends FormRequest
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
            'sort_number' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'カテゴリ名',
            'sort_number' => '表示順',
        ];
    }
}
