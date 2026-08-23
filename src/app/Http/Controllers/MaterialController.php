<?php

namespace App\Http\Controllers;

use App\Application\Contexts\Authentication\AuthenticationService;
use App\Application\Contexts\Material\UseCases\CreateMaterialUseCase;
use App\Application\Contexts\Material\UseCases\GetMaterialDetailUseCase;
use App\Application\Contexts\Material\UseCases\StoreMaterialUseCase;
use App\Http\Flash\Flash;
use App\Http\Requests\Material\CreateRequest;
use App\Http\Requests\Material\ShowRequest;
use App\Http\Requests\Material\StoreRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class MaterialController extends Controller
{
    public function __construct(
        private readonly AuthenticationService $auth,
    ) {}

    public function show(ShowRequest $request, GetMaterialDetailUseCase $useCase): Response
    {
        $material = $useCase->execute($request->toQuery());

        return Inertia::render('Material/Show', [
            'material' => $material,
        ]);
    }

    public function create(CreateRequest $request, CreateMaterialUseCase $useCase): Response
    {
        $useCase->execute(
            $request->toCommand(
                $this->auth->requireUser()->requireId()
            )
        );

        return Inertia::render('Material/Create', [
            'offering' => [
                'id' => (int) $request->route('id'),
            ],
        ]);
    }

    public function store(StoreRequest $request, StoreMaterialUseCase $useCase): RedirectResponse
    {
        $useCase->execute(
            $request->toCommand(
                $this->auth->requireUser()->requireId()
            )
        );

        return redirect()->route('course-offerings.show', ['id' => $request->route('id')])
            ->with(Flash::success('資料をアップロードしました'));
    }
}
