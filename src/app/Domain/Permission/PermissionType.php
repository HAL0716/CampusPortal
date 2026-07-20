<?php

namespace App\Domain\Permission;

enum PermissionType: string
{
    case DashboardView = 'dashboard.view';

    case UserView = 'user.view';

    case TEST = 'test';
}
