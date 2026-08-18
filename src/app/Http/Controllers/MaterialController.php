<?php

namespace App\Http\Controllers;

use App\Application\Contexts\Authentication\AuthenticationService;
use App\Application\Contexts\Material\UseCases\CreateMaterialUseCase;
use App\Exceptions\UserMessageException;
use App\Http\Controllers\Concerns\HasFlashMessages;
use App\Http\Requests\Material\StoreRequest;
use Illuminate\Http\RedirectResponse;
use Throwable;

class MaterialController extends Controller
{
    use HasFlashMessages;

    public function __construct(
        private readonly AuthenticationService $auth,
    ) {}

    public function store(StoreRequest $request, CreateMaterialUseCase $useCase): RedirectResponse
    {
        try {
            $useCase->execute(
                $request->toCommand(
                    $this->auth->requireUser()->requireId()
                )
            );

            return back()->with($this->withSuccess('資料をアップロードしました'));
        } catch (UserMessageException $e) {
            return back()->with($this->withError($e->userMessage()));
        } catch (Throwable $e) {
            report($e);

            return back()->with($this->withError('資料のアップロードに失敗しました'));
        }
    }
}
