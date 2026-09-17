<?php

namespace Tests\Feature\Http\Controllers;

use App\Domain\Academic\Enums\Term;
use App\Domain\Permission\Enums\PermissionType;
use App\Domain\Role\Enums\RoleType;
use App\Models\Course;
use App\Models\CourseOffering;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Semester;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class SemesterControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            PermissionSeeder::class,
            RoleSeeder::class,
        ]);
    }

    public function test_can_view_semesters(): void
    {
        $admin = $this->createAdmin();

        [$latest, $previous] = Semester::factory()
            ->count(2)
            ->sequence(
                [
                    'academic_year' => '2026',
                    'term' => Term::SECOND,
                    'start_date' => '2026-09-01',
                    'end_date' => '2026-12-31',
                ],
                [
                    'academic_year' => '2026',
                    'term' => Term::FIRST,
                    'start_date' => '2026-04-01',
                    'end_date' => '2026-08-31',
                ],
            )
            ->create();

        $this->actingAs($admin)
            ->get(route('semesters.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Semester/Index')
                ->has('semesters', 2)
                ->where('semesters.0.id', $latest->id)
                ->where('semesters.1.id', $previous->id)
            );
    }

    public function test_cannot_view_semesters_without_permission(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('semesters.index'))
            ->assertForbidden();
    }

    public function test_can_view_semester_create_form(): void
    {
        $admin = $this->createAdmin();

        $semester = Semester::factory()->create([
            'academic_year' => '2026',
            'term' => Term::FIRST,
            'start_date' => '2026-04-01',
            'end_date' => '2026-08-31',
        ]);

        $this->actingAs($admin)
            ->get(route('semesters.create'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Semester/Create')
                ->has('latestSemester')
                ->where('latestSemester.id', $semester->id)
            );
    }

    public function test_cannot_view_semester_create_form_without_permission(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('semesters.create'))
            ->assertForbidden();
    }

    public function test_can_create_semester(): void
    {
        $admin = $this->createAdmin();

        $semester = Semester::factory()->create([
            'academic_year' => '2026',
            'term' => Term::FIRST,
            'start_date' => '2026-04-01',
            'end_date' => '2026-08-31',
        ]);

        Course::factory()->createMany([
            ['id' => 1, 'term' => Term::FIRST],
            ['id' => 2, 'term' => Term::SECOND],
        ]);

        $endDate = $semester->end_date->modify('+4 months');

        $this->actingAs($admin)
            ->post(route('semesters.store'), [
                'endDate' => $endDate->format('Y-m-d'),
            ])
            ->assertRedirect(route('semesters.index'))
            ->assertSessionHas('success');

        $nextSemester = Semester::query()->latest('id')->firstOrFail();

        $this->assertDatabaseHas('semesters', [
            'id' => $nextSemester->id,
            'term' => Term::SECOND,
            'start_date' => $semester->end_date->modify('+1 day'),
            'end_date' => $endDate,
        ]);

        $this->assertDatabaseCount('course_offerings', 1);

        $this->assertDatabaseHas('course_offerings', [
            'course_id' => 2,
            'semester_id' => $nextSemester->id,
        ]);
    }

    public function test_cannot_create_semester_without_permission(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('semesters.store'), [
                'endDate' => '2026-12-31',
            ])
            ->assertForbidden();
    }

    public function test_can_view_semester_details(): void
    {
        $admin = $this->createAdmin();

        $semester = Semester::factory()->create([
            'academic_year' => '2026',
            'term' => Term::FIRST,
            'start_date' => '2026-04-01',
            'end_date' => '2026-08-31',
        ]);

        $courseOffering = CourseOffering::factory()
            ->for($semester)
            ->create();

        $this->actingAs($admin)
            ->get(route('semesters.show', $semester->id))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Semester/Show')
                ->has('semester')
                ->where('semester.id', $semester->id)
                ->has('semester.courseOfferings', 1)
                ->where('semester.courseOfferings.0.id', $courseOffering->id)
            );
    }

    public function test_cannot_view_semester_details_without_permission(): void
    {
        $user = User::factory()->create();

        $semester = Semester::factory()->create([
            'academic_year' => '2026',
            'term' => Term::FIRST,
            'start_date' => '2026-04-01',
            'end_date' => '2026-08-31',
        ]);

        $this->actingAs($user)
            ->get(route('semesters.show', $semester->id))
            ->assertForbidden();
    }

    private function createAdmin(): User
    {
        $role = Role::query()
            ->where('name', RoleType::ADMIN)
            ->firstOrFail();

        $permission = Permission::query()
            ->where('name', PermissionType::SemesterManage)
            ->firstOrFail();

        $role->permissions()->syncWithoutDetaching($permission);

        return User::factory()
            ->withRoles([$role])
            ->create();
    }
}
