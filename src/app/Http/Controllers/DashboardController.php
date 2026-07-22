<?php

namespace App\Http\Controllers;

use App\Application\CourseOffering\ListCourseOfferingsUseCase;
use App\Http\Requests\Dashboard\DashboardRequest;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(DashboardRequest $request, ListCourseOfferingsUseCase $useCase): Response
    {
        return Inertia::render('Dashboard/Index', [
            'offerings' => $useCase->execute($request->toQuery()),
        ]);
    }
}
