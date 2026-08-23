<?php

namespace Tests\Feature\Infrastructure\QueryServices;

use App\Application\Contexts\Material\Services\MaterialQueryService;
use App\Domain\Material\Exceptions\MaterialNotFoundException;
use App\Domain\Material\ValueObjects\MaterialId;
use App\Models\Material;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\Clock\UseClock;
use Tests\TestCase;

final class EloquentMaterialQueryServiceTest extends TestCase
{
    use RefreshDatabase;
    use UseClock;

    private MaterialQueryService $queryService;

    private CarbonImmutable $now;

    protected function setUp(): void
    {
        parent::setUp();

        $this->now = CarbonImmutable::parse('2026-04-01 00:00:00');

        $this->useClock($this->now);
        $this->queryService = app(MaterialQueryService::class);
    }

    public function test_can_find_detail_with_published_material(): void
    {
        $material = Material::factory()->create([
            'title' => '公開済み資料',
            'publish_date' => $this->now->subHour(),
        ]);

        $dto = $this->queryService->getDetail(new MaterialId($material->id));

        $this->assertSame($material->id, $dto->id);
        $this->assertSame($material->title, $dto->title);
    }

    public function test_can_find_detail_with_material_published_at_now(): void
    {
        $material = Material::factory()->create([
            'title' => '公開時刻の資料',
            'publish_date' => $this->now,
        ]);

        $dto = $this->queryService->getDetail(new MaterialId($material->id));

        $this->assertSame($material->id, $dto->id);
    }

    public function test_cannot_find_detail_with_unpublished_material(): void
    {
        $material = Material::factory()->create([
            'title' => '未公開資料',
            'publish_date' => $this->now->addHour(),
        ]);

        $this->expectException(MaterialNotFoundException::class);

        $this->queryService->getDetail(new MaterialId($material->id));
    }
}
