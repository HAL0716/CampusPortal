<?php

namespace App\Http\Requests\Student;

use App\Application\Contexts\Student\Commands\CreateStudentCommand;
use App\Domain\Department\ValueObjects\DepartmentId;
use App\Domain\Student\ValueObjects\StudentNumber;
use App\Domain\User\ValueObjects\UserEmail;
use App\Domain\User\ValueObjects\UserPassword;
use App\Rules\Student\StudentNumberRule;
use App\Rules\User\UserEmailRule;
use App\Rules\User\UserPasswordRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'email' => ['required', new UserEmailRule],
            'password' => ['required', new UserPasswordRule],
            'name' => ['required', 'string', 'max:255'],
            'studentNumber' => ['required', new StudentNumberRule],
            'departmentId' => ['required', 'integer', 'exists:departments,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'メールアドレス',
            'password' => 'パスワード',
            'name' => '名前',
            'studentNumber' => '学籍番号',
            'departmentId' => '学科',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute は必須です。',
            'string' => ':attribute が不正な形式です。',
            'integer' => ':attribute が不正な形式です。',
            'exists' => ':attribute が存在しません。',
        ];
    }

    public function toCommand(): CreateStudentCommand
    {
        return new CreateStudentCommand(
            email: new UserEmail($this->validated('email')),
            password: UserPassword::create($this->validated('password')),
            name: $this->validated('name'),
            studentNumber: new StudentNumber($this->validated('studentNumber')),
            departmentId: new DepartmentId((int) $this->validated('departmentId')),
        );
    }
}
