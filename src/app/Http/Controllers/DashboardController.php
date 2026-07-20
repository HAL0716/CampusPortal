<?php

namespace App\Http\Controllers;

use App\Application\CourseOffering\ListCourseOfferingUseCase;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(ListCourseOfferingUseCase $useCase): Response
    {
        return Inertia::render('Dashboard/Index', [
            'courseOfferings' => $useCase->execute(),
        ]);
    }
}
