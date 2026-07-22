export interface CourseOffering {
  id: number;
  name: string;
  status?: 'enrolled' | 'dropped' | 'completed' | null;
}
