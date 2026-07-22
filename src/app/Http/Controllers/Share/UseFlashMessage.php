<?php

namespace App\Http\Controllers\Share;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

trait UseFlashMessage
{
    protected function backWithFlash(string $type, string $message): RedirectResponse
    {
        return back()->with($type, [
            'id' => (string) Str::uuid(),
            'message' => $message,
        ]);
    }

    protected function backWithSuccess(string $message): RedirectResponse
    {
        return $this->backWithFlash('success', $message);
    }

    protected function backWithError(string $message): RedirectResponse
    {
        return $this->backWithFlash('error', $message);
    }
}
