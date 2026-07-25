<?php

namespace App\Http\Controllers;

use App\Application\Authentication\AuthenticationServiceInterface;
use App\Application\Authorization\PermissionServiceInterface;
use App\Application\Clock\ClockInterface;
use App\Application\CourseOffering\Administration\ListCourseOfferingsUseCase as ListAdministrationCourseOfferingsUseCase;
use App\Application\CourseOffering\Enrollment\ListCourseOfferingsUseCase as ListEnrollmentCourseOfferingsUseCase;
use App\Application\CourseOffering\Management\ListCourseOfferingsUseCase as ListManagementCourseOfferingsUseCase;
use App\Domain\Permission\PermissionType;
use App\Http\Requests\Dashboard\DashboardRequest;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private readonly ClockInterface $clock,
        private readonly AuthenticationServiceInterface $auth,
        private readonly PermissionServiceInterface $permissions,
    ) {}

    public function index(
        DashboardRequest $request,
        ListAdministrationCourseOfferingsUseCase $administrationUseCase,
        ListEnrollmentCourseOfferingsUseCase $enrollmentUseCase,
        ListManagementCourseOfferingsUseCase $managementUseCase,
    ): Response {
        $user = $this->auth->requireUser();
        $now = $this->clock->now();

        $offerings = match (true) {
            $this->permissions->can($user, PermissionType::CourseOfferingManagementAll) => $administrationUseCase->execute(
                $request->toAdministrationQuery($now)
            ),
            $this->permissions->can($user, PermissionType::CourseOfferingManagement) => $managementUseCase->execute(
                $request->toManagementQuery($now, $user->requireId())
            ),
            $this->permissions->can($user, PermissionType::CourseOfferingEnrollment) => $enrollmentUseCase->execute(
                $request->toEnrollmentQuery($now, $user->requireId())
            ),
            default => [],
        };

        $mode = match (true) {
            $this->permissions->can($user, PermissionType::CourseOfferingManagementAll) => 'administration',
            $this->permissions->can($user, PermissionType::CourseOfferingManagement) => 'management',
            $this->permissions->can($user, PermissionType::CourseOfferingEnrollment) => 'enrollment',
            default => null,
        };

        return Inertia::render('Dashboard/Index', [
            'offerings' => $offerings,
            'courseOfferingMode' => $mode,
        ]);
    }
}
