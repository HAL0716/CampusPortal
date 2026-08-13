<?php

namespace App\Http\Controllers;

use App\Application\Authentication\AuthenticationServiceInterface;
use App\Application\Authorization\PermissionServiceInterface;
use App\Domain\Permission\PermissionType;
use App\Domain\User\Entities\User;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private readonly AuthenticationServiceInterface $auth,
        private readonly PermissionServiceInterface $permissions,
    ) {}

    public function index(): Response
    {
        $user = $this->auth->requireUser();

        return Inertia::render('Dashboard/Index', [
            'courseOffering' => $this->courseOffering($user),
        ]);
    }

    private function courseOffering(User $user): ?array
    {
        return match (true) {
            $this->permissions->can($user, PermissionType::CourseOfferingAdministration) => [
                'route' => 'course-offerings.administration',
                'label' => '開講管理',
            ],
            $this->permissions->can($user, PermissionType::CourseOfferingManagement) => [
                'route' => 'course-offerings.management',
                'label' => '授業管理',
            ],
            $this->permissions->can($user, PermissionType::CourseOfferingEnrollment) => [
                'route' => 'course-offerings.enrollment',
                'label' => '履修登録',
            ],
            default => null,
        };
    }
}
