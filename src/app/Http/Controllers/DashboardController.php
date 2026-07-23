<?php

namespace App\Http\Controllers;

use App\Application\Authentication\AuthenticationServiceInterface;
use App\Application\CourseOffering\ListCourseOfferingsUseCase;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Http\Requests\Dashboard\DashboardRequest;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private readonly AuthenticationServiceInterface $auth,
    ) {}

    public function index(DashboardRequest $request, ListCourseOfferingsUseCase $useCase): Response
    {
        $user = $this->auth->user();
        if ($user === null) {
            throw new UserNotFoundException;
        }

        return Inertia::render('Dashboard/Index', [
            'offerings' => $useCase->execute($request->toQuery($user->requireId())),
        ]);
    }
}
