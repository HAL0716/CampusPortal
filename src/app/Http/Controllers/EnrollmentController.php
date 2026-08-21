<?php

namespace App\Http\Controllers;

use App\Application\Contexts\Authentication\AuthenticationService;
use App\Application\Contexts\Enrollment\UseCases\DropUseCase;
use App\Application\Contexts\Enrollment\UseCases\EnrollUseCase;
use App\Application\Contexts\Enrollment\UseCases\FinalizeGradeUseCase;
use App\Exceptions\UserMessageException;
use App\Http\Flash\Flash;
use App\Http\Requests\Enrollment\DropRequest;
use App\Http\Requests\Enrollment\EnrollRequest;
use App\Http\Requests\Enrollment\FinalizeGradeRequest;
use Illuminate\Http\RedirectResponse;
use Throwable;

final class EnrollmentController extends Controller
{
    public function __construct(
        private readonly AuthenticationService $auth,
    ) {}

    public function enroll(EnrollRequest $request, EnrollUseCase $useCase): RedirectResponse
    {
        try {
            $useCase->execute(
                $request->toCommand(
                    $this->auth->requireUser()->requireId()
                )
            );

            return back()->with(Flash::success('履修登録しました'));
        } catch (UserMessageException $e) {
            return back()->with(Flash::error($e->userMessage()));
        } catch (Throwable $e) {
            report($e);

            return back()->with(Flash::error('履修登録に失敗しました'));
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

            return back()->with(Flash::success('履修取消しました'));
        } catch (UserMessageException $e) {
            return back()->with(Flash::error($e->userMessage()));
        } catch (Throwable $e) {
            report($e);

            return back()->with(Flash::error('履修取消に失敗しました'));
        }
    }

    public function complete(FinalizeGradeRequest $request, FinalizeGradeUseCase $useCase): RedirectResponse
    {
        try {
            $useCase->execute(
                $request->toCommand(
                    $this->auth->requireUser()->requireId()
                )
            );

            return back()->with(Flash::success('履修完了しました'));
        } catch (UserMessageException $e) {
            return back()->with(Flash::error($e->userMessage()));
        } catch (Throwable $e) {
            report($e);

            return back()->with(Flash::error('履修完了に失敗しました'));
        }
    }
}
