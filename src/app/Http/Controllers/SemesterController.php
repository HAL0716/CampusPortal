<?php

namespace App\Http\Controllers;

use App\Application\Contexts\Semester\UseCases\AddNextSemesterUseCase;
use App\Application\Contexts\Semester\UseCases\GetLatestSemesterUseCase;
use App\Application\Contexts\Semester\UseCases\ListSemesterUseCase;
use App\Http\Flash\Flash;
use App\Http\Requests\Semester\StoreRequest;
use Illuminate\Http\RedirectResponse;
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

    public function create(GetLatestSemesterUseCase $useCase): Response
    {
        $latestSemester = $useCase->execute();

        return Inertia::render('Semester/Create', [
            'latestSemester' => $latestSemester,
        ]);
    }

    public function store(StoreRequest $request, AddNextSemesterUseCase $useCase): RedirectResponse
    {
        $useCase->execute($request->toCommand());

        return to_route('semesters.index')
            ->with(Flash::success('学期を追加しました。'));
    }
}
