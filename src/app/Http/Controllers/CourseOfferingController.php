<?php

namespace App\Http\Controllers;

use App\Application\Clock\ClockInterface;
use App\Application\CourseOffering\Administration\ListCourseOfferingsUseCase as AdministrationUseCase;
use App\Application\CourseOffering\Enrollment\ListCourseOfferingsUseCase as EnrollmentUseCase;
use App\Application\CourseOffering\Management\ListCourseOfferingsUseCase as ManagementUseCase;
use App\Application\Services\Authentication\AuthenticationService;
use App\Http\Requests\CourseOffering\AdministrationRequest;
use App\Http\Requests\CourseOffering\EnrollmentRequest;
use App\Http\Requests\CourseOffering\ManagementRequest;
use Inertia\Inertia;

class CourseOfferingController extends Controller
{
    public function __construct(
        private readonly ClockInterface $clock,
        private readonly AuthenticationService $auth,
    ) {}

    public function enrollment(EnrollmentRequest $request, EnrollmentUseCase $useCase)
    {
        return Inertia::render('CourseOffering/Enrollment', [
            'offerings' => $useCase->execute(
                $request->toQuery(
                    date: $this->clock->now(),
                    userId: $this->auth->requireUser()->requireId()
                )
            ),
        ]);
    }

    public function management(ManagementRequest $request, ManagementUseCase $useCase)
    {
        return Inertia::render('CourseOffering/Management', [
            'offerings' => $useCase->execute(
                $request->toQuery(
                    date: $this->clock->now(),
                    userId: $this->auth->requireUser()->requireId()
                )
            ),
        ]);
    }

    public function administration(AdministrationRequest $request, AdministrationUseCase $useCase)
    {
        return Inertia::render('CourseOffering/Administration', [
            'offerings' => $useCase->execute(
                $request->toQuery(
                    date: $this->clock->now(),
                )
            ),
        ]);
    }
}
