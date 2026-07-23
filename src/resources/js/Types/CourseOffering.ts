export type CourseOffering = {
  id: number;
  name: string;
  status: 'enrolled' | 'dropped' | 'completed' | null;
};
