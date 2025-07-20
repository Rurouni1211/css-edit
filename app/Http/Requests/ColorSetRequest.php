<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ColorSetRequest extends FormRequest
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
        $rules = [
            'material_id' => [
                'required',
                'integer',
                'exists:materials,id',
            ],
            'color_set_details' => [
                'required',
                'array',
            ],
            'color_set_details.*.color_code' => [
                'required',
                'string',
                'regex:/^#[0-9a-fA-F]{6}$/',
            ],
        ];

        if($this->isMethod('post')) {

            $rules['material_id'][] = 'unique:color_sets,material_id';

        } else if($this->isMethod('put')) {

            $rules['material_id'][] = 'unique:color_sets,material_id,' . $this->route('color_set')->id;

        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'material_id' => '素材',
            'color_set_details' => 'カラーセット',
            'color_set_details.*.color_code' => 'カラーコード',
        ];
    }

    public function messages()
    {
        return [
            'color_set_details.*.color_code.regex' => '16進数（例：#ffffff）のカラーコードを入力してください。',
        ];
    }
}
