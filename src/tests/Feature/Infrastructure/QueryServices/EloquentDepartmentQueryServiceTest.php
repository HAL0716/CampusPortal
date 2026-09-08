<?php

namespace Tests\Feature\Infrastructure\QueryServices;

use App\Application\Contexts\Department\DTOs\DepartmentDTO;
use App\Infrastructure\QueryServices\EloquentDepartmentQueryService;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class EloquentDepartmentQueryServiceTest extends TestCase
{
    use RefreshDatabase;

    private function queryService(): EloquentDepartmentQueryService
    {
        return app(EloquentDepartmentQueryService::class);
    }

    public function test_find_all_returns_departments_in_name_order(): void
    {
        $departments = [
            ['name' => '情報システム学科'],
            ['name' => '経済学科'],
            ['name' => '工学科'],
        ];

        Department::factory()->createMany($departments);

        $expected = array_column($departments, 'name');
        sort($expected);

        $result = $this->queryService()->findAll();
        $actual = array_map(
            fn (DepartmentDTO $department) => $department->name,
            $result,
        );

        self::assertCount(count($departments), $result);
        self::assertContainsOnlyInstancesOf(DepartmentDTO::class, $result);
        self::assertSame($expected, $actual);
    }

    public function test_find_all_returns_department_information(): void
    {
        $department = Department::factory()->create([
            'name' => '情報システム学科',
        ]);

        $result = $this->queryService()->findAll();

        self::assertCount(1, $result);
        self::assertSame($department->id, $result[0]->id);
        self::assertSame($department->name, $result[0]->name);
    }

    public function test_find_all_returns_empty_when_no_departments_exist(): void
    {
        self::assertSame([], $this->queryService()->findAll());
    }
}
