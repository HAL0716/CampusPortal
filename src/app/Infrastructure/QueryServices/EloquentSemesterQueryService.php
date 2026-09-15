<?php

namespace App\Infrastructure\QueryServices;

use App\Application\Contexts\Semester\DTOs\CourseOfferingDTO;
use App\Application\Contexts\Semester\DTOs\SemesterDetailDTO;
use App\Application\Contexts\Semester\DTOs\SemesterDTO;
use App\Application\Contexts\Semester\Services\SemesterQueryService;
use App\Domain\Semester\Exceptions\SemesterNotFoundException;
use App\Domain\Semester\ValueObjects\SemesterId;
use App\Models\Semester;

final readonly class EloquentSemesterQueryService implements SemesterQueryService
{
    public function getDetail(SemesterId $semesterId): SemesterDetailDTO
    {
        $semester = Semester::query()
            ->select([
                'id',
                'academic_year',
                'term',
                'start_date',
                'end_date',
            ])
            ->with([
                'courseOfferings' => fn ($query) => $query
                    ->select([
                        'id',
                        'course_id',
                        'semester_id',
                    ])
                    ->with([
                        'course:id,name,description',
                    ]),
            ])
            ->find($semesterId->value());

        if ($semester === null) {
            throw new SemesterNotFoundException;
        }

        return new SemesterDetailDTO(
            id: $semester->id,
            academicYear: $semester->academic_year,
            term: $semester->term->value,
            startDate: $semester->start_date->format('Y-m-d'),
            endDate: $semester->end_date->format('Y-m-d'),
            courseOfferings: $semester->courseOfferings
                ->map(
                    fn ($courseOffering) => new CourseOfferingDTO(
                        id: $courseOffering->id,
                        name: $courseOffering->course->name,
                        description: $courseOffering->course->description,
                    )
                )
                ->all(),
        );
    }

    /** @return array<SemesterDTO> */
    public function findAll(): array
    {
        return Semester::query()
            ->orderBy('end_date', 'desc')
            ->select([
                'id',
                'academic_year',
                'term',
                'start_date',
                'end_date',
            ])
            ->get()
            ->map(
                fn ($semester) => new SemesterDTO(
                    id: $semester->id,
                    academicYear: $semester->academic_year,
                    term: $semester->term->value,
                    startDate: $semester->start_date->format('Y-m-d'),
                    endDate: $semester->end_date->format('Y-m-d'),
                )
            )
            ->all();
    }

    public function getLatest(): SemesterDTO
    {
        $semester = Semester::query()
            ->orderBy('end_date', 'desc')
            ->select([
                'id',
                'academic_year',
                'term',
                'start_date',
                'end_date',
            ])
            ->first();

        if ($semester === null) {
            throw new SemesterNotFoundException;
        }

        return new SemesterDTO(
            id: $semester->id,
            academicYear: $semester->academic_year,
            term: $semester->term->value,
            startDate: $semester->start_date->format('Y-m-d'),
            endDate: $semester->end_date->format('Y-m-d'),
        );
    }
}
