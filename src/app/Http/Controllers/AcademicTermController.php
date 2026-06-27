<?php

namespace App\Http\Controllers;

use App\Http\Resources\AcademicTermResource;
use App\Models\AcademicTerm;
use Inertia\Inertia;
use Inertia\Response;

class AcademicTermController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('AcademicTerms/Index', [
            'academicTerms' => AcademicTermResource::collection(
                AcademicTerm::query()
                    ->orderBy('academic_year', 'desc')
                    ->get()
            )->resolve(),
        ]);
    }
}
