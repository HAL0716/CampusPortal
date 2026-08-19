<?php

namespace App\Http\Controllers;

use App\Application\Contexts\Authentication\AuthenticationService;
use App\Application\Contexts\FinalGrade\UseCases\ListEnrollmentsUseCase;
use App\Http\Requests\FinalGrade\IndexRequest;
use Inertia\Inertia;
use Inertia\Response;

final class FinalGradeController extends Controller
{
    public function __construct(
        private readonly AuthenticationService $auth,
    ) {}

    public function index(IndexRequest $request, ListEnrollmentsUseCase $useCase): Response
    {
        return Inertia::render('FinalGrade/Index', [
            'enrollments' => $useCase->execute(
                $request->toQuery(
                    $this->auth->requireUser()->requireId()
                )
            ),
        ]);
    }
}
