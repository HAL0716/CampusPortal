<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => '有効なメールアドレスを入力してください。',
            'password.required' => 'パスワードを入力してください。',
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $user = User::withTrashed()
            ->where('email', $this->email)
            ->first();

        if ($user && $user->trashed()) {
            throw ValidationException::withMessages([
                'status' => 'このアカウントは利用できません。',
            ]);
        }

        if (! $user || ! Hash::check($this->password, $user->password)) {
            RateLimiter::hit(
                $this->throttleKey(),
                config('auth.login.decay_seconds')
            );

            throw ValidationException::withMessages([
                'status' => 'メールアドレスまたはパスワードが正しくありません。',
            ]);
        }

        Auth::login($user);

        RateLimiter::clear($this->throttleKey());
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts(
            $this->throttleKey(),
            config('auth.login.max_attempts')
        )) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'status' => "{$seconds}秒後に再試行してください。",
        ]);
    }

    protected function throttleKey(): string
    {
        return strtolower(trim($this->string('email')->toString())).'|'.$this->ip();
    }
}
