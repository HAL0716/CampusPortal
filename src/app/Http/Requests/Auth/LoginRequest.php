<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login_id' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'login_id' => 'ログインID',
            'password' => 'パスワード',
        ];
    }

    public function messages(): array
    {
        return [
            'login_id.required' => ':attributeを入力してください',
            'password.required' => ':attributeを入力してください',
        ];
    }

    public function authenticate(): void
    {
        if (! Auth::attempt($this->only('login_id', 'password'))) {
            throw ValidationException::withMessages([
                'login_id' => 'ログインIDまたはパスワードが正しくありません。',
            ]);
        }
    }
}
