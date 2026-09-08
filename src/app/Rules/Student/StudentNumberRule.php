<?php

namespace App\Rules\Student;

use App\Domain\Student\ValueObjects\StudentNumber;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use InvalidArgumentException;

final class StudentNumberRule implements ValidationRule
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
            new StudentNumber($value);
        } catch (InvalidArgumentException) {
            $fail(':attribute が不正な形式です。');
        }
    }
}
