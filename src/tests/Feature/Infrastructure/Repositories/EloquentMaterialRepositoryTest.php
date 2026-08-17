<?php

namespace Tests\Feature\Infrastructure\Repositories;

use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\Material\Entities\Material;
use App\Domain\Material\Exceptions\MaterialNotFoundException;
use App\Domain\Material\ValueObjects\MaterialId;
use App\Infrastructure\Repositories\EloquentMaterialRepository;
use App\Models\CourseOffering;
use App\Models\Material as MaterialModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class EloquentMaterialRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EloquentMaterialRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(EloquentMaterialRepository::class);
    }

    public function test_can_save_new_material(): void
    {
        $offering = CourseOffering::factory()->create();

        $saved = $this->repository->save($this->material(new CourseOfferingId($offering->id)));

        self::assertNotNull($saved->id());
        self::assertDatabaseHas('materials', [
            'id' => $saved->id()->value(),
            'course_offering_id' => $offering->id,
            'title' => '第1回講義資料',
            'file_path' => 'materials/test.pdf',
        ]);
    }

    public function test_can_update_existing_material(): void
    {
        $model = MaterialModel::factory()->create();

        $saved = $this->repository->save(Material::reconstruct(
            new MaterialId($model->id),
            new CourseOfferingId($model->course_offering_id),
            '更新後の資料',
            '更新後の説明',
            'materials/updated.pdf',
            null,
        ));

        self::assertSame($model->id, $saved->id()->value());
        self::assertSame('更新後の資料', $saved->title());
        self::assertDatabaseHas('materials', [
            'id' => $model->id,
            'title' => '更新後の資料',
            'file_path' => 'materials/updated.pdf',
        ]);
    }

    public function test_throws_exception_when_material_not_found(): void
    {
        $offering = CourseOffering::factory()->create();

        self::expectException(MaterialNotFoundException::class);

        $this->repository->save(Material::reconstruct(
            new MaterialId(999999),
            new CourseOfferingId($offering->id),
            '資料',
            null,
            'materials/test.pdf',
            null,
        ));
    }

    private function material(CourseOfferingId $courseOfferingId): Material
    {
        return Material::create(
            courseOfferingId: $courseOfferingId,
            title: '第1回講義資料',
            description: '講義資料です。',
            filePath: 'materials/test.pdf',
            publishDate: null,
        );
    }
}
