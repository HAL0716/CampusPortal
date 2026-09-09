<?php

namespace App\Rules\User;

use App\Domain\User\Exceptions\InvalidUserPasswordException;
use App\Domain\User\ValueObjects\UserPassword;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class UserPasswordRule implements ValidationRule
{
    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail,
    ): void {
        if (! is_string($value)) {
            $fail(':attribute が不正な形式です。');

            return;
        }

        try {
            UserPassword::create($value);
        } catch (InvalidUserPasswordException) {
            $fail(':attribute が不正な形式です。');
        }
    }
}
