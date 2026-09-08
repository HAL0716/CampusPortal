<?php

namespace App\Http\Controllers;

use App\Application\Contexts\Authentication\AuthenticationService;
use App\Application\Services\Authorization\PermissionAuthorizationService;
use App\Domain\Permission\Enums\PermissionType;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private AuthenticationService $auth,
        private PermissionAuthorizationService $permission,
    ) {}

    public function index(): Response
    {
        $user = $this->auth->requireUser();

        return Inertia::render('Dashboard/Index', [
            'canManageStudents' => $this->permission->can($user, PermissionType::StudentManage),
        ]);
    }
}
