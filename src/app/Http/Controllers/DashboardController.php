<?php

namespace App\Http\Controllers;

use App\Application\Authentication\AuthenticationServiceInterface;
use App\Application\Clock\ClockInterface;
use App\Application\CourseOffering\ListCourseOfferingsUseCase;
use App\Http\Requests\Dashboard\DashboardRequest;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private readonly ClockInterface $clock,
        private readonly AuthenticationServiceInterface $auth,
    ) {}

    public function index(DashboardRequest $request, ListCourseOfferingsUseCase $useCase): Response
    {
        return Inertia::render('Dashboard/Index', [
            'offerings' => $useCase->execute(
                $request->toQuery(
                    $this->clock->now(),
                    $this->auth->requireUser()->requireId()
                )
            ),
        ]);
    }
}
