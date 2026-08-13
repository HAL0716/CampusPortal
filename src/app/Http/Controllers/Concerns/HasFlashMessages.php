<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Support\Str;

trait HasFlashMessages
{
    protected function withSuccess(string $message): array
    {
        return [
            'success' => [
                'id' => Str::uuid()->toString(),
                'message' => $message,
            ],
        ];
    }

    protected function withError(string $message): array
    {
        return [
            'error' => [
                'id' => Str::uuid()->toString(),
                'message' => $message,
            ],
        ];
    }
}
