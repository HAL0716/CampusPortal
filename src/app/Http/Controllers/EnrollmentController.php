<?php

namespace App\Http\Controllers;

use App\Application\Authentication\AuthenticationServiceInterface;
use App\Application\Enrollment\CompleteUseCase;
use App\Application\Enrollment\DropUseCase;
use App\Application\Enrollment\EnrollUseCase;
use App\Http\Controllers\Concerns\HasFlashMessages;
use App\Http\Requests\Enrollment\CompleteRequest;
use App\Http\Requests\Enrollment\DropRequest;
use App\Http\Requests\Enrollment\EnrollRequest;
use Illuminate\Http\RedirectResponse;
use Throwable;

final class EnrollmentController extends Controller
{
    use HasFlashMessages;

    public function __construct(
        private readonly AuthenticationServiceInterface $auth,
    ) {}

    public function enroll(EnrollRequest $request, EnrollUseCase $useCase): RedirectResponse
    {
        try {
            $useCase->execute(
                $request->toCommand(
                    $this->auth->requireUser()->requireId()
                )
            );

            return back()->with($this->withSuccess('履修登録しました'));
        } catch (Throwable $e) {
            return back()->with($this->withError($e->getMessage()));
        }
    }

    public function drop(DropRequest $request, DropUseCase $useCase): RedirectResponse
    {
        try {
            $useCase->execute(
                $request->toCommand(
                    $this->auth->requireUser()->requireId()
                )
            );

            return back()->with($this->withSuccess('履修取消しました'));
        } catch (Throwable $e) {
            return back()->with($this->withError($e->getMessage()));
        }
    }

    public function complete(CompleteRequest $request, CompleteUseCase $useCase): RedirectResponse
    {
        try {
            $useCase->execute(
                $request->toCommand(
                    $this->auth->requireUser()->requireId()
                )
            );

            return back()->with($this->withSuccess('履修完了しました'));
        } catch (Throwable $e) {
            return back()->with($this->withError($e->getMessage()));
        }
    }
}
