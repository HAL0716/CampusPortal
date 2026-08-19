<?php

namespace App\Http\Controllers;

use App\Application\Contexts\Authentication\AuthenticationService;
use App\Application\Contexts\Material\UseCases\StoreMaterialUseCase;
use App\Exceptions\UserMessageException;
use App\Http\Controllers\Concerns\HasFlashMessages;
use App\Http\Requests\Material\CreateRequest;
use App\Http\Requests\Material\StoreRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class MaterialController extends Controller
{
    use HasFlashMessages;

    public function __construct(
        private readonly AuthenticationService $auth,
    ) {}

    public function create(CreateRequest $request): Response
    {
        return Inertia::render('Material/Create', [
            'offering' => [
                'id' => $request->route('id'),
            ],
        ]);
    }

    public function store(StoreRequest $request, StoreMaterialUseCase $useCase): RedirectResponse
    {
        try {
            $useCase->execute(
                $request->toCommand(
                    $this->auth->requireUser()->requireId()
                )
            );

            return redirect()->route('course-offerings.show', ['id' => $request->route('id')])
                ->with($this->withSuccess('資料をアップロードしました'));
        } catch (UserMessageException $e) {
            return back()->with($this->withError($e->userMessage()));
        } catch (Throwable $e) {
            report($e);

            return back()->with($this->withError('資料のアップロードに失敗しました'));
        }
    }
}
