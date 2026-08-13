<?php

namespace App\Http\Requests\CourseOffering;

use App\Application\CourseOffering\Enrollment\ListCourseOfferingsQuery;
use App\Domain\User\ValueObjects\UserId;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EnrollmentRequest extends FormRequest
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

    public function toQuery(CarbonImmutable $date, UserId $userId): ListCourseOfferingsQuery
    {
        return new ListCourseOfferingsQuery(
            date: $date,
            userId: $userId,
        );
    }
}
