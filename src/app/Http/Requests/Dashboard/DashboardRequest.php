<?php

namespace App\Http\Requests\Dashboard;

use App\Application\CourseOffering\ListCourseOfferingsQuery;
use App\Domain\Academic\Term;
use App\Domain\User\UserId;
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

    public function toQuery(UserId $userId): ListCourseOfferingsQuery
    {
        return new ListCourseOfferingsQuery(
            academicYear: '2025', // TODO: 動的取得
            term: Term::FIRST,    // TODO: 動的取得
            userId: $userId,
        );
    }
}
