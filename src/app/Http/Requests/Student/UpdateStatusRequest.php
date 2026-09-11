<?php

namespace App\Http\Requests\Student;

use App\Application\Contexts\Student\Commands\UpdateStudentStatusCommand;
use App\Domain\Student\Enums\StudentStatus;
use App\Domain\Student\ValueObjects\StudentId;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateStatusRequest extends FormRequest
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
            'status' => ['required', new Enum(StudentStatus::class)],
            'credits' => ['required', 'integer', 'min:0'],
        ];
    }

    public function attributes(): array
    {
        return [
            'status' => 'ステータス',
            'credits' => '修得単位数',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute は必須です。',
            'enum' => ':attribute が不正な値です。',
            'integer' => ':attribute は整数である必要があります。',
            'min' => ':attribute は :min 以上である必要があります。',
        ];
    }

    public function toCommand(): UpdateStudentStatusCommand
    {
        return new UpdateStudentStatusCommand(
            studentId: new StudentId((int) $this->route('student')),
            status: StudentStatus::from($this->validated('status')),
            credits: (int) $this->validated('credits'),
        );
    }
}
