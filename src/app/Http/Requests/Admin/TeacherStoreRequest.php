<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TeacherStoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'unique:users,email'],
            'department_id' => ['required', 'exists:departments,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => '名前',
            'email' => 'メールアドレス',
            'department_id' => '所属学科',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => ':attributeを入力してください',
            'email.required' => ':attributeを入力してください',
            'email.email' => ':attributeの形式が正しくありません',
            'email.unique' => 'この:attributeは既に使用されています',
            'department_id.required' => ':attributeを選択してください',
            'department_id.exists' => '選択された:attributeは存在しません',
        ];
    }

    public function userData(string $password): array
    {
        return [
            'login_id' => strtok($this->email, '@'),
            'name' => $this->name,
            'email' => $this->email,
            'password' => $password,
            'role' => UserRole::TEACHER,
        ];
    }

    public function teacherData(int $userId): array
    {
        return [
            'user_id' => $userId,
            'department_id' => $this->department_id,
        ];
    }
}
