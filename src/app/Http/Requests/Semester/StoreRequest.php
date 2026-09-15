<?php

namespace App\Http\Requests\Semester;

use App\Application\Contexts\Semester\Commands\AddNextSemesterCommand;
use DateTimeImmutable;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'endDate' => ['required', 'date'],
        ];
    }

    public function attributes(): array
    {
        return [
            'endDate' => '終了日',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute は必須です。',
            'date' => ':attribute が不正な形式です。',
        ];
    }

    public function toCommand(): AddNextSemesterCommand
    {
        return new AddNextSemesterCommand(
            endDate: new DateTimeImmutable($this->validated('endDate')),
        );
    }
}
