<?php

namespace App\Application\Auth;

use Illuminate\Support\Facades\Auth;

final class LoginUseCase
{
    public function execute(LoginCommand $command): bool
    {
        return Auth::attempt([
            'email' => $command->email,
            'password' => $command->password,
        ]);
    }
}
