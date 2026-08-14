<?php

namespace App\Http\Requests\Enrollment;

use App\Application\Contexts\Enrollment\DropCommand;
use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\User\ValueObjects\UserId;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DropRequest extends FormRequest
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

    public function toCommand(UserId $userId): DropCommand
    {
        return new DropCommand(
            userId: $userId,
            courseOfferingId: new CourseOfferingId($this->route('courseOffering')),
        );
    }
}
