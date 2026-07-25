export type Student = {
  enrollmentId: number;
  studentId: number;
  studentNumber: string;
};

type CourseOfferingBase = {
  id: number;
  name: string;
};

export type EnrollmentCourseOffering = CourseOfferingBase & {
  status: 'enrolled' | 'dropped' | 'completed' | null;
};

export type ManagementCourseOffering = CourseOfferingBase & {
  students: Student[];
};

export type AdministrationCourseOffering = CourseOfferingBase;
