<?php

namespace App\Http\Requests\Student;

use App\Application\Contexts\Student\Queries\GetStudentQuery;
use App\Domain\Student\ValueObjects\StudentId;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ShowRequest extends FormRequest
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
            //
        ];
    }

    public function toQuery(): GetStudentQuery
    {
        return new GetStudentQuery(
            new StudentId((int) $this->route('student')),
        );
    }
}
