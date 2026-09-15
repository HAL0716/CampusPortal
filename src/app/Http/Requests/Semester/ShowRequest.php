<?php

namespace App\Http\Requests\Semester;

use App\Application\Contexts\Semester\Queries\GetSemesterQuery;
use App\Domain\Semester\ValueObjects\SemesterId;
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

    public function toQuery(): GetSemesterQuery
    {
        return new GetSemesterQuery(
            new SemesterId((int) $this->route('semester')),
        );
    }
}
