<?php

namespace App\Http\Requests\Enrollment;

use App\Application\Enrollment\EnrollCommand;
use App\Domain\CourseOffering\CourseOfferingId;
use App\Domain\User\UserId;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EnrollRequest extends FormRequest
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

    public function toCommand(UserId $userId): EnrollCommand
    {
        return new EnrollCommand(
            userId: $userId,
            courseOfferingId: new CourseOfferingId($this->route('courseOffering')),
        );
    }
}
