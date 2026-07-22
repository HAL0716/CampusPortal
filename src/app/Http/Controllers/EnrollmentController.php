<?php

namespace App\Http\Controllers;

use App\Application\Authentication\AuthenticationServiceInterface;
use App\Application\Enrollment\EnrollUseCase;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Http\Requests\Enrollment\EnrollRequest;
use Illuminate\Http\RedirectResponse;
use Throwable;

final class EnrollmentController extends Controller
{
    public function __construct(
        private readonly AuthenticationServiceInterface $auth,
    ) {}

    public function enroll(EnrollRequest $request, EnrollUseCase $useCase): RedirectResponse
    {
        try {
            $user = $this->auth->user();
            if ($user === null) {
                throw new UserNotFoundException;
            }

            $useCase->execute($request->toCommand($user->requireId()));

            return back()->with('success', '履修登録しました');
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
