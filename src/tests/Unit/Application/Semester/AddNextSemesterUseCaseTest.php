<?php

namespace Tests\Unit\Application\Semester;

use App\Application\Contexts\Semester\Commands\AddNextSemesterCommand;
use App\Application\Contexts\Semester\UseCases\AddNextSemesterUseCase;
use App\Application\Services\Database\Transaction;
use App\Domain\Academic\Enums\Term;
use App\Domain\Course\Repositories\CourseRepository;
use App\Domain\CourseOffering\Entities\CourseOffering;
use App\Domain\CourseOffering\Repositories\CourseOfferingRepository;
use App\Domain\Semester\Exceptions\InvalidEndDate;
use App\Domain\Semester\Exceptions\SemesterNotFoundException;
use App\Domain\Semester\Repositories\SemesterRepository;
use App\Domain\Semester\ValueObjects\AcademicYear;
use DateTimeImmutable;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Tests\Support\TestHelpers\CourseTestHelper;
use Tests\Support\TestHelpers\SemesterTestHelper;

final class AddNextSemesterUseCaseTest extends TestCase
{
    use CourseTestHelper;
    use MockeryPHPUnitIntegration;
    use SemesterTestHelper;

    private SemesterRepository&MockInterface $semesters;

    private CourseRepository&MockInterface $courses;

    private CourseOfferingRepository&MockInterface $courseOfferings;

    private Transaction&MockInterface $transaction;

    private AddNextSemesterUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->semesters = Mockery::mock(SemesterRepository::class);
        $this->courses = Mockery::mock(CourseRepository::class);
        $this->courseOfferings = Mockery::mock(CourseOfferingRepository::class);
        $this->transaction = Mockery::mock(Transaction::class);

        $this->transaction
            ->shouldReceive('run')
            ->once()
            ->andReturnUsing(
                fn (callable $callback): mixed => $callback(),
            );

        $this->useCase = new AddNextSemesterUseCase(
            semesters: $this->semesters,
            courses: $this->courses,
            courseOfferings: $this->courseOfferings,
            transaction: $this->transaction,
        );
    }

    public function test_adds_next_semester_and_course_offerings(): void
    {
        $latest = $this->createSemester(
            academicYear: new AcademicYear('2026'),
            term: Term::FIRST,
            startDate: new DateTimeImmutable('2026-01-01'),
            endDate: new DateTimeImmutable('2026-03-31'),
        );

        $next = $this->reconstructSemester(
            academicYear: new AcademicYear('2026'),
            term: Term::SECOND,
            startDate: new DateTimeImmutable('2026-04-01'),
            endDate: new DateTimeImmutable('2026-06-30'),
        );

        $course1 = $this->reconstructCourse(id: 1);
        $course2 = $this->reconstructCourse(id: 2);

        $saved = [];

        $this->semesters->shouldReceive('getLatest')
            ->once()
            ->andReturn($latest);

        $this->semesters->shouldReceive('save')
            ->once()
            ->andReturn($next);

        $this->courses->shouldReceive('getByTerm')
            ->once()
            ->with(Term::SECOND)
            ->andReturn([$course1, $course2]);

        $this->courseOfferings->shouldReceive('save')
            ->twice()
            ->andReturnUsing(
                function (CourseOffering $courseOffering) use (&$saved): CourseOffering {
                    $saved[] = $courseOffering;

                    return $courseOffering;
                },
            );

        $this->useCase->execute(
            new AddNextSemesterCommand(
                endDate: $next->endDate(),
            ),
        );

        self::assertCount(2, $saved);

        self::assertEqualsCanonicalizing(
            [$course1->requireId(), $course2->requireId()],
            [$saved[0]->courseId(), $saved[1]->courseId()],
        );

        self::assertSame($next->requireId(), $saved[0]->semesterId());
        self::assertSame($next->requireId(), $saved[1]->semesterId());
    }

    public function test_adds_next_semester_without_course_offerings_when_no_courses_match_term(): void
    {
        $latest = $this->createSemester(
            academicYear: new AcademicYear('2026'),
            term: Term::FIRST,
            startDate: new DateTimeImmutable('2026-01-01'),
            endDate: new DateTimeImmutable('2026-03-31'),
        );

        $next = $this->reconstructSemester(
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
            ->andReturn($next);

        $this->courses->shouldReceive('getByTerm')
            ->once()
            ->with(Term::SECOND)
            ->andReturn([]);

        $this->courseOfferings->shouldNotReceive('save');

        $this->useCase->execute(
            new AddNextSemesterCommand(
                endDate: $next->endDate(),
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

        $next = $this->reconstructSemester(
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
            ->andReturn($next);

        $this->courses->shouldReceive('getByTerm')
            ->once()
            ->with(Term::FIRST)
            ->andReturn([]);

        $this->courseOfferings->shouldNotReceive('save');

        $this->useCase->execute(
            new AddNextSemesterCommand(
                endDate: $next->endDate(),
            ),
        );
    }

    public function test_throws_exception_when_no_latest_semester_exists(): void
    {
        $this->semesters->shouldReceive('getLatest')
            ->once()
            ->andThrow(SemesterNotFoundException::class);

        $this->semesters->shouldNotReceive('save');
        $this->courses->shouldNotReceive('getByTerm');
        $this->courseOfferings->shouldNotReceive('save');

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
        $this->courses->shouldNotReceive('getByTerm');
        $this->courseOfferings->shouldNotReceive('save');

        $this->expectException(InvalidEndDate::class);

        $this->useCase->execute(
            new AddNextSemesterCommand(
                endDate: new DateTimeImmutable('2026-03-30'),
            ),
        );
    }
}
