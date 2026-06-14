<?php

namespace App\Services;

use Illuminate\Support\Str;

class PasswordGenerator
{
    public function generate(): string
    {
        return Str::password(
            length: 8,
            letters: true,
            numbers: true,
            symbols: false,
        );
    }
}
