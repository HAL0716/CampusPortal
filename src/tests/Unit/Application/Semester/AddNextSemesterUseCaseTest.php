<?php

namespace Tests\Unit\Application\Semester;

use App\Application\Contexts\Semester\Commands\AddNextSemesterCommand;
use App\Application\Contexts\Semester\UseCases\AddNextSemesterUseCase;
use App\Domain\Academic\Enums\Term;
use App\Domain\Semester\Entities\Semester;
use App\Domain\Semester\Exceptions\InvalidEndDate;
use App\Domain\Semester\Exceptions\SemesterNotFoundException;
use App\Domain\Semester\Repositories\SemesterRepository;
use App\Domain\Semester\ValueObjects\AcademicYear;
use DateTimeImmutable;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Tests\Support\TestHelpers\SemesterTestHelper;

final class AddNextSemesterUseCaseTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    use SemesterTestHelper;

    private SemesterRepository&MockInterface $semesters;

    private AddNextSemesterUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->semesters = Mockery::mock(SemesterRepository::class);
        $this->useCase = new AddNextSemesterUseCase($this->semesters);
    }

    public function test_adds_next_semester(): void
    {
        $latest = $this->createSemester(
            academicYear: new AcademicYear('2026'),
            term: Term::FIRST,
            startDate: new DateTimeImmutable('2026-01-01'),
            endDate: new DateTimeImmutable('2026-03-31'),
        );

        $expected = $this->createSemester(
            academicYear: new AcademicYear('2026'),
            term: Term::SECOND,
            startDate: new DateTimeImmutable('2026-04-01'),
            endDate: new DateTimeImmutable('2026-06-30'),
        );

        $this->semesters->shouldReceive('getLatest')
            ->once()
            ->andReturn($latest);

        $this->semesters->shouldReceive('save')
            ->once()
            ->with(Mockery::on(
                fn (Semester $actual): bool => $actual->academicYear()->value() === $expected->academicYear()->value()
                    && $actual->term() === $expected->term()
                    && $actual->startDate() == $expected->startDate()
                    && $actual->endDate() == $expected->endDate()
                    && $actual->id() === null,
            ))
            ->andReturnArg(0);

        $this->useCase->execute(
            new AddNextSemesterCommand(
                endDate: $expected->endDate(),
            ),
        );
    }

    public function test_adds_first_semester_of_next_academic_year_after_third_term(): void
    {
        $latest = $this->createSemester(
            academicYear: new AcademicYear('2026'),
            term: Term::THIRD,
            startDate: new DateTimeImmutable('2027-01-01'),
            endDate: new DateTimeImmutable('2027-03-31'),
        );

        $expected = $this->createSemester(
            academicYear: new AcademicYear('2027'),
            term: Term::FIRST,
            startDate: new DateTimeImmutable('2027-04-01'),
            endDate: new DateTimeImmutable('2027-06-30'),
        );

        $this->semesters->shouldReceive('getLatest')
            ->once()
            ->andReturn($latest);

        $this->semesters->shouldReceive('save')
            ->once()
            ->with(Mockery::on(
                fn (Semester $actual): bool => $actual->academicYear()->value() === $expected->academicYear()->value()
                    && $actual->term() === $expected->term()
                    && $actual->startDate() == $expected->startDate()
                    && $actual->endDate() == $expected->endDate()
                    && $actual->id() === null,
            ))
            ->andReturnArg(0);

        $this->useCase->execute(
            new AddNextSemesterCommand(
                endDate: $expected->endDate(),
            ),
        );
    }

    public function test_throws_exception_when_no_latest_semester_exists(): void
    {
        $this->semesters->shouldReceive('getLatest')
            ->once()
            ->andThrow(SemesterNotFoundException::class);

        $this->semesters->shouldNotReceive('save');

        $this->expectException(SemesterNotFoundException::class);

        $this->useCase->execute(
            new AddNextSemesterCommand(
                endDate: new DateTimeImmutable('2026-06-30'),
            ),
        );
    }

    public function test_throws_exception_when_end_date_is_before_latest_semester_end_date(): void
    {
        $latest = $this->createSemester(
            academicYear: new AcademicYear('2026'),
            term: Term::FIRST,
            startDate: new DateTimeImmutable('2026-01-01'),
            endDate: new DateTimeImmutable('2026-03-31'),
        );

        $this->semesters->shouldReceive('getLatest')
            ->once()
            ->andReturn($latest);

        $this->semesters->shouldNotReceive('save');

        $this->expectException(InvalidEndDate::class);

        $this->useCase->execute(
            new AddNextSemesterCommand(
                endDate: new DateTimeImmutable('2026-03-30'),
            ),
        );
    }
}
