<?php

namespace App\Http\Requests\CourseOffering;

use App\Application\Contexts\CourseOffering\Administration\Queries\ListCourseOfferingsQuery;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AdministrationRequest extends FormRequest
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

    public function toQuery(CarbonImmutable $date): ListCourseOfferingsQuery
    {
        return new ListCourseOfferingsQuery(
            date: $date,
        );
    }
}
