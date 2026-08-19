<?php

namespace App\Http\Controllers;

use App\Application\Contexts\Authentication\AuthenticationService;
use App\Application\Contexts\CourseOffering\Index\UseCases\ListCourseOfferingsUseCase;
use App\Application\Contexts\CourseOffering\Show\UseCases\GetCourseOfferingUseCase;
use App\Application\Services\Clock\Clock;
use App\Http\Requests\CourseOffering\IndexRequest;
use App\Http\Requests\CourseOffering\ShowRequest;
use Inertia\Inertia;
use Inertia\Response;

class CourseOfferingController extends Controller
{
    public function __construct(
        private readonly Clock $clock,
        private readonly AuthenticationService $auth,
    ) {}

    public function index(IndexRequest $request, ListCourseOfferingsUseCase $useCase): Response
    {
        $offerings = $useCase->execute(
            $request->toQuery(
                date: $this->clock->now(),
                userId: $this->auth->requireUser()->requireId()
            )
        );

        return Inertia::render('CourseOffering/Index', [
            'offerings' => $offerings,
        ]);
    }

    public function show(ShowRequest $request, GetCourseOfferingUseCase $useCase)
    {
        return Inertia::render('CourseOffering/Show', [
            'offering' => $useCase->execute(
                $request->toQuery(
                    userId: $this->auth->requireUser()->requireId()
                )
            ),
        ]);
    }
}
