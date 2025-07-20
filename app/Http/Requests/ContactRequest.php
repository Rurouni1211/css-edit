<?php

namespace App\Http\Requests;

use App\Enums\ContactSubjectType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactRequest extends FormRequest
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
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'email_confirmation' => ['required', 'string', 'email', 'max:255', 'same:email'],
            'phone' => ['required', 'string', 'max:20'],
            'body' => 'required|string|max:1000',
        ];
    }

    public function attributes()
    {
        return [
            'last_name' => '姓',
            'first_name' => '名',
            'email' => 'メールアドレス',
            'email_confirmation' => 'メールアドレス確認用',
            'phone' => '電話番号',
            'body' => 'お問い合わせ内容',
        ];
    }

    public function messages()
    {
        return [
            'email_confirmation.same' => 'メールアドレスが一致しません。',
        ];
    }
}
