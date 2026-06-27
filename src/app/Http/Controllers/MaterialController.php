<?php

namespace App\Http\Controllers;

use App\Http\Resources\LectureMaterialResource;
use App\Models\CourseOffering;
use App\Models\LectureMaterial;
use App\Support\SystemClock;
use Inertia\Inertia;
use Inertia\Response;

class MaterialController extends Controller
{
    public function show(CourseOffering $offering, LectureMaterial $material): Response
    {
        abort_unless(
            $material->course_offering_id === $offering->id,
            404
        );

        abort_unless(
            $material->published_at <= SystemClock::now(),
            404
        );

        return Inertia::render('Materials/Show', [
            'material' => (new LectureMaterialResource($material))
                ->resolve(),
        ]);
    }
}
