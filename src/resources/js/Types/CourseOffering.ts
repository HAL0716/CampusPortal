export type Student = {
  id: number;
  studentNumber: string;
  status: 'enrolled' | 'dropped' | 'completed';
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
