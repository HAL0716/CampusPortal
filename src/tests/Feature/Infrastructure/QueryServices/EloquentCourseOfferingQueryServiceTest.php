<?php

namespace Tests\Feature\Infrastructure\QueryServices;

use App\Application\Contexts\CourseOffering\Services\CourseOfferingQueryService;
use App\Application\Contexts\CourseOffering\Show\DTOs\CourseOfferingDTO as DetailDTO;
use App\Application\Contexts\CourseOffering\Show\DTOs\MaterialDTO;
use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Models\Course;
use App\Models\CourseOffering;
use App\Models\Material;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\Clock\UseClock;
use Tests\TestCase;

final class EloquentCourseOfferingQueryServiceTest extends TestCase
{
    use RefreshDatabase;
    use UseClock;

    private CourseOfferingQueryService $queryService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useClock('2026-08-18 12:00:00');

        $this->queryService = app(CourseOfferingQueryService::class);
    }

    public function test_can_find_detail_with_published_materials(): void
    {
        $user = User::factory()->create(['name' => '山田太郎']);
        $teacher = Teacher::factory()->for($user)->create();

        $course = Course::factory()->create([
            'name' => '数学',
            'description' => '数学の講義です。',
        ]);
        $course->teachers()->attach($teacher);

        $offering = CourseOffering::factory()->create([
            'course_id' => $course->id,
        ]);

        Material::factory()->create([
            'course_offering_id' => $offering->id,
            'title' => '公開済み資料',
            'publish_date' => '2026-08-18 10:00:00',
        ]);

        Material::factory()->create([
            'course_offering_id' => $offering->id,
            'title' => '未公開資料',
            'publish_date' => '2026-08-18 13:00:00',
        ]);

        Material::factory()->create([
            'course_offering_id' => $offering->id,
            'title' => '即時公開資料',
            'publish_date' => null,
        ]);

        $result = $this->queryService->findDetail(
            new CourseOfferingId($offering->id),
        );

        $this->assertInstanceOf(DetailDTO::class, $result);
        $this->assertSame($offering->id, $result->id);
        $this->assertSame('数学', $result->name);
        $this->assertSame('数学の講義です。', $result->description);
        $this->assertSame(['山田太郎'], $result->teachers);

        $this->assertCount(2, $result->materials);
        $this->assertSame(
            ['即時公開資料', '公開済み資料'],
            array_map(
                fn (MaterialDTO $material) => $material->title,
                $result->materials,
            ),
        );
    }
}
