<?php

namespace Tests\Feature\Infrastructure\Repositories;

use App\Domain\Material\Entities\Material;
use App\Domain\Material\Exceptions\MaterialNotFoundException;
use App\Infrastructure\Repositories\EloquentMaterialRepository;
use App\Models\CourseOffering;
use App\Models\Material as MaterialModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\TestHelpers\MaterialTestHelper;
use Tests\TestCase;

final class EloquentMaterialRepositoryTest extends TestCase
{
    use MaterialTestHelper;
    use RefreshDatabase;

    private function repository(): EloquentMaterialRepository
    {
        return app(EloquentMaterialRepository::class);
    }

    public function test_save_creates_material(): void
    {
        $material = $this->createMaterial(
            courseOfferingId: CourseOffering::factory()->create()->id,
        );

        $result = $this->repository()->save($material);

        self::assertInstanceOf(Material::class, $result);
        self::assertNotNull($result->id());
        self::assertSame($material->courseOfferingId()->value(), $result->courseOfferingId()->value());
        self::assertSame($material->title(), $result->title());

        $this->assertDatabaseHas('materials', [
            'id' => $result->requireId()->value(),
            'course_offering_id' => $material->courseOfferingId()->value(),
            'title' => $material->title(),
        ]);
    }

    public function test_save_updates_existing_material(): void
    {
        $model = MaterialModel::factory()->create();

        $material = $this->reconstructMaterial(
            id: $model->id,
            courseOfferingId: $model->course_offering_id,
            title: '更新後の資料',
            description: '更新後の説明',
            filePath: 'materials/updated.pdf',
        );

        $result = $this->repository()->save($material);

        self::assertSame($material->requireId()->value(), $result->requireId()->value());
        self::assertSame($material->title(), $result->title());
        self::assertSame($material->description(), $result->description());
        self::assertSame($material->filePath(), $result->filePath());

        $this->assertDatabaseHas('materials', [
            'id' => $material->requireId()->value(),
            'title' => $material->title(),
            'description' => $material->description(),
            'file_path' => $material->filePath(),
        ]);
    }

    public function test_save_throws_exception_when_updating_nonexistent_material(): void
    {
        $material = $this->reconstructMaterial(
            id: 999999,
            courseOfferingId: CourseOffering::factory()->create()->id,
        );

        $this->expectException(MaterialNotFoundException::class);

        $this->repository()->save($material);
    }

    public function test_get_by_id_returns_material(): void
    {
        $model = MaterialModel::factory()->create();

        $result = $this->repository()->getById($this->materialId($model->id));

        self::assertInstanceOf(Material::class, $result);
        self::assertSame($model->id, $result->requireId()->value());
    }

    public function test_get_by_id_throws_exception_when_material_not_found(): void
    {
        $this->expectException(MaterialNotFoundException::class);

        $this->repository()->getById($this->materialId(999999));
    }
}
