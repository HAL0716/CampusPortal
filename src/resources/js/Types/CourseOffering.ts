type Status = 'enrolled' | 'dropped' | 'completed' | 'failed';

export type Enrollment = {
  id: number;
  studentNumber: string;
  status: Status;
};

type CourseOfferingBase = {
  id: number;
  name: string;
};

export type EnrollmentCourseOffering = CourseOfferingBase & {
  status: Status | null; // null: 履修前
};

export type ManagementCourseOffering = CourseOfferingBase & {
  enrollments: Enrollment[];
};

export type AdministrationCourseOffering = CourseOfferingBase;
