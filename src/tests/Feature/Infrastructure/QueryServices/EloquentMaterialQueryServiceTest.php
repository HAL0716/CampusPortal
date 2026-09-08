<?php

namespace Tests\Feature\Infrastructure\QueryServices;

use App\Application\Contexts\Material\DTOs\MaterialDetailDTO;
use App\Domain\Material\Exceptions\MaterialNotFoundException;
use App\Domain\Material\ValueObjects\MaterialId;
use App\Infrastructure\QueryServices\EloquentMaterialQueryService;
use App\Models\CourseOffering;
use App\Models\Material;
use App\Models\Semester;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\Clock\UseClock;
use Tests\TestCase;

final class EloquentMaterialQueryServiceTest extends TestCase
{
    use RefreshDatabase;
    use UseClock;

    private Semester $semester;

    private CourseOffering $offering;

    private CarbonImmutable $now;

    protected function setUp(): void
    {
        parent::setUp();

        $this->semester = Semester::factory()->create();

        $this->offering = CourseOffering::factory()
            ->for($this->semester)
            ->create();

        $startDate = CarbonImmutable::instance(
            $this->semester->start_date,
        );

        $endDate = CarbonImmutable::instance(
            $this->semester->end_date,
        );

        $this->now = $startDate->addDays(
            intdiv($startDate->diffInDays($endDate), 2),
        );

        $this->useClock($this->now);
    }

    private function queryService(): EloquentMaterialQueryService
    {
        return app(EloquentMaterialQueryService::class);
    }

    public function test_get_detail_returns_published_material(): void
    {
        $material = Material::factory()
            ->for($this->offering)
            ->create([
                'publish_date' => $this->now->subHour(),
            ]);

        $result = $this->queryService()->getDetail(
            new MaterialId($material->id),
        );

        self::assertInstanceOf(MaterialDetailDTO::class, $result);

        self::assertSame($material->id, $result->id);
        self::assertSame($material->title, $result->title);
        self::assertSame($material->description, $result->description);
        self::assertSame($material->file_path, $result->filePath);
    }

    public function test_get_detail_returns_material_published_at_now(): void
    {
        $material = Material::factory()
            ->for($this->offering)
            ->create([
                'publish_date' => $this->now,
            ]);

        $result = $this->queryService()->getDetail(
            new MaterialId($material->id),
        );

        self::assertSame($material->id, $result->id);
    }

    public function test_get_detail_throws_exception_for_unpublished_material(): void
    {
        $material = Material::factory()
            ->for($this->offering)
            ->create([
                'publish_date' => $this->now->addHour(),
            ]);

        $this->expectException(MaterialNotFoundException::class);

        $this->queryService()->getDetail(
            new MaterialId($material->id),
        );
    }

    public function test_get_detail_returns_material_without_publish_date(): void
    {
        $material = Material::factory()
            ->for($this->offering)
            ->create([
                'publish_date' => null,
            ]);

        $result = $this->queryService()->getDetail(
            new MaterialId($material->id),
        );

        self::assertSame($material->id, $result->id);
    }

    public function test_get_detail_throws_exception_for_non_existent_material(): void
    {
        $this->expectException(MaterialNotFoundException::class);

        $this->queryService()->getDetail(
            new MaterialId(999999),
        );
    }

    public function test_get_detail_does_not_return_another_material(): void
    {
        $material = Material::factory()
            ->for($this->offering)
            ->create([
                'publish_date' => $this->now->subHour(),
            ]);

        $anotherMaterial = Material::factory()
            ->for($this->offering)
            ->create([
                'publish_date' => $this->now->subHour(),
            ]);

        $result = $this->queryService()->getDetail(
            new MaterialId($material->id),
        );

        self::assertSame($material->id, $result->id);
        self::assertNotSame($anotherMaterial->id, $result->id);
    }
}
