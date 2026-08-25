<?php

namespace App\Http\Requests\CourseOffering;

use App\Application\Contexts\CourseOffering\Show\Queries\GetCourseOfferingQuery;
use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\User\ValueObjects\UserId;
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

    public function toQuery(UserId $userId): GetCourseOfferingQuery
    {
        return new GetCourseOfferingQuery(
            courseOfferingId: new CourseOfferingId((int) $this->route('courseOffering')),
            userId: $userId,
        );
    }
}
