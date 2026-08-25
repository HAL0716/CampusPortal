<?php

namespace App\Http\Requests\Material;

use App\Application\Contexts\Material\Commands\CreateMaterialCommand;
use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\User\ValueObjects\UserId;
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

    public function toCommand(UserId $userId): CreateMaterialCommand
    {
        return new CreateMaterialCommand(
            new CourseOfferingId((int) $this->route('courseOffering')),
            $userId,
        );
    }
}
