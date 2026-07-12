<?php

namespace App\Rules\User;

use App\Domain\User\Exceptions\InvalidUserEmailException;
use App\Domain\User\UserEmail;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class UserEmailRule implements ValidationRule
{
    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail,
    ): void {
        try {
            new UserEmail($value);
        } catch (InvalidUserEmailException) {
            $fail(':attribute が不正な形式です。');
        }
    }
}
