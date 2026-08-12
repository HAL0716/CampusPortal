type Status = 'enrolled' | 'dropped' | 'completed' | 'failed';

export type Student = {
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
  students: Student[];
};

export type AdministrationCourseOffering = CourseOfferingBase;
