<?php

namespace App\Domain\Permission\Enums;

enum PermissionType: string
{
    case DashboardView = 'dashboard.view';

    case CourseOfferingEnrollment = 'course_offering.enrollment';
    case CourseOfferingManagement = 'course_offering.management';
    case CourseOfferingAdministration = 'course_offering.administration';
}
