<?php

namespace App\Http\Requests;

use App\Models\Material;
use App\Rules\MaterialColorCode;
use App\Rules\MaterialKey;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MaterialRequest extends FormRequest
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
        // Material モデルから素材ボタンキーの配列を取得
        $materialKeys = Material::getMaterialButtonKeys();

        return [
            'name' => ['required', 'string', 'max:255'],
            'key' => [
                'required', 
                'string', 
                'max:255',
                'regex:/^[a-zA-Z0-9_]+$/', // 英数字のみ（全角も通る場合があるので、alpha_numは使わない）
            ],
            'glossiness' => [
                'required',
                'numeric',
                'min:0',
                'max:1',
                'regex:/^[0-1](\.[0-9]{1,3})?$/',
            ],
            'specular' => [
                'required',
                'numeric',
                'min:0',
                'max:1',
                'regex:/^[0-1](\.[0-9]{1,3})?$/',
            ],
            'colors' => ['required', 'array'],
            'colors.*.name' => ['required', 'string', 'max:255'],
            'colors.*.color_code' => [
                new MaterialColorCode(),
            ],
            'colors.*.texture_file' => ['nullable', 'file', 'image'],
            'normal_maps' => ['required', 'array'],
            'normal_maps.*.name' => ['required', 'string', 'max:255'],
            'normal_maps.*.file' => ['nullable', 'file', 'image'],
            'specular_map.file' => ['nullable', 'file', 'image'],
        ];
    }

    public function attributes()
    {
        return [
            'name' => '素材名',
            'key' => 'キー',
            'glossiness' => '光沢度',
            'specular' => '鏡面反射',
            'colors' => 'カラー',
            'colors.*.name' => '名前',
            'colors.*.color_code' => 'カラーコード',
            'colors.*.texture_file' => 'テクスチャファイル',
            'normal_maps' => 'ノーマルマップ',
            'normal_maps.*.name' => '名前',
            'normal_maps.*.file' => 'ファイル',
            'specular_map.file' => 'ファイル',
        ];
    }

    public function messages()
    {
        return [
            'glossiness.regex' => ':attributeは、小数点第3位までの数値で入力してください。',
            'specular.regex' => ':attributeは、小数点第3位までの数値で入力してください。',
            'colors.*.color_code.regex' => ':attributeは、#から始まる6桁の16進数で入力してください。',
        ];
    }
}
