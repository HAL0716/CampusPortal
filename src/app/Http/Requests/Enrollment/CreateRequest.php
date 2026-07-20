<?php

namespace App\Http\Requests\Enrollment;

use App\Application\Enrollment\CreateEnrollmentCommand;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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

    public function toCommand(): CreateEnrollmentCommand
    {
        return new CreateEnrollmentCommand(
            courseOfferingId: (int) $this->route('courseOffering'),
        );
    }
}
