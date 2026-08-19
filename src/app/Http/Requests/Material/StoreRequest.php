<?php

namespace App\Http\Requests\Material;

use App\Application\Contexts\Material\Commands\StoreMaterialCommand;
use App\Application\Services\Storage\UploadFile;
use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\User\ValueObjects\UserId;
use DateTimeImmutable;
use Illuminate\Contracts\Validation\ValidationRule;
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'mimes:txt,pdf,doc,docx', 'max:10240'], // 10MB
            'publishDate' => ['nullable', 'date'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'タイトル',
            'description' => '説明',
            'file' => 'ファイル',
            'publishDate' => '公開日',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute は必須です。',
            'string' => ':attribute が不正な形式です。',
            'file' => ':attribute が不正な形式です。',
            'date' => ':attribute が不正な形式です。',
        ];
    }

    public function toCommand(UserId $userId): StoreMaterialCommand
    {
        return new StoreMaterialCommand(
            userId: $userId,
            courseOfferingId: new CourseOfferingId($this->route('id')),
            title: $this->validated('title'),
            description: $this->validated('description'),
            file: $this->uploadFile(),
            publishDate: $this->validated('publishDate') ? new DateTimeImmutable($this->validated('publishDate')) : null,
        );
    }

    private function uploadFile(): ?UploadFile
    {
        $file = $this->file('file');

        if ($file === null) {
            return null;
        }

        return new UploadFile(
            originalName: $file->getClientOriginalName(),
            mimeType: $file->getMimeType(),
            size: $file->getSize(),
            contents: $file->get(),
        );
    }
}
