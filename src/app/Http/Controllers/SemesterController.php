<?php

namespace App\Http\Controllers;

use App\Application\Contexts\Semester\UseCases\ListSemesterUseCase;
use Inertia\Inertia;
use Inertia\Response;

final class SemesterController extends Controller
{
    public function index(ListSemesterUseCase $useCase): Response
    {
        $semesters = $useCase->execute();

        return Inertia::render('Semester/Index', [
            'semesters' => $semesters,
        ]);
    }
}
