<?php

namespace App\Domain\Permission\Enums;

enum PermissionType: string
{
    case DashboardView = 'dashboard.view';

    case StudentManage = 'student.manage';

    case CourseOfferingView = 'course_offering.view';
    case CourseOfferingManage = 'course_offering.manage';

    case EnrollmentManage = 'enrollment.manage';

    case FinalGradeCreate = 'final_grade.create';

    case MaterialView = 'material.view';
    case MaterialCreate = 'material.create';
}
