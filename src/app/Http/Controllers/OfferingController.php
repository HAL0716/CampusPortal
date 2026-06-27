<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseOfferingResource;
use App\Models\CourseOffering;
use Inertia\Inertia;
use Inertia\Response;

class OfferingController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Offerings/Index', [
            'offerings' => CourseOfferingResource::collection(
                CourseOffering::currentTerm()
                    ->with([
                        'course',
                        'teacher.user',
                    ])
                    ->get()
            )->resolve(),
        ]);
    }
}
