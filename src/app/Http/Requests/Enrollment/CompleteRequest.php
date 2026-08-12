<?php

namespace App\Http\Requests\Enrollment;

use App\Application\Enrollment\CompleteCommand;
use App\Domain\Enrollment\EnrollmentId;
use App\Domain\FinalGrade\FinalGradeType;
use App\Domain\User\UserId;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompleteRequest extends FormRequest
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
            'grade' => ['required', Rule::enum(FinalGradeType::class)],
        ];
    }

    public function toCommand(UserId $userId): CompleteCommand
    {
        return new CompleteCommand(
            userId: $userId,
            enrollmentId: new EnrollmentId($this->route('enrollment')),
            grade: FinalGradeType::from($this->validated('grade')),
        );
    }
}
