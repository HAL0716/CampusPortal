<?php

namespace App\Http\Requests\Material;

use App\Application\Contexts\Material\Commands\DownloadMaterialCommand;
use App\Domain\Material\ValueObjects\MaterialId;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DownloadRequest extends FormRequest
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

    public function toCommand(): DownloadMaterialCommand
    {
        return new DownloadMaterialCommand(
            materialId: new MaterialId((int) $this->route('material')),
        );
    }
}
