<?php

namespace App\Http\Requests\Enrollment;

use App\Application\Enrollment\CompleteCommand;
use App\Domain\Enrollment\EnrollmentId;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

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
            //
        ];
    }

    public function toCommand(): CompleteCommand
    {
        return new CompleteCommand(
            enrollmentId: new EnrollmentId($this->route('enrollment')),
        );
    }
}
