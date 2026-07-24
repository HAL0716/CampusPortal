<?php

namespace App\Http\Requests\Dashboard;

use App\Application\CourseOffering\Administration\ListCourseOfferingsQuery as ListAdministrationCourseOfferingsQuery;
use App\Application\CourseOffering\Enrollment\ListCourseOfferingsQuery as ListEnrollmentCourseOfferingsQuery;
use App\Application\CourseOffering\Management\ListCourseOfferingsQuery as ListManagementCourseOfferingsQuery;
use App\Domain\User\UserId;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DashboardRequest extends FormRequest
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

    public function toEnrollmentQuery(CarbonImmutable $date, UserId $userId): ListEnrollmentCourseOfferingsQuery
    {
        return new ListEnrollmentCourseOfferingsQuery(
            date: $date,
            userId: $userId,
        );
    }

    public function toManagementQuery(CarbonImmutable $date, UserId $userId): ListManagementCourseOfferingsQuery
    {
        return new ListManagementCourseOfferingsQuery(
            date: $date,
            userId: $userId,
        );
    }

    public function toAdministrationQuery(CarbonImmutable $date): ListAdministrationCourseOfferingsQuery
    {
        return new ListAdministrationCourseOfferingsQuery(
            date: $date,
        );
    }
}
