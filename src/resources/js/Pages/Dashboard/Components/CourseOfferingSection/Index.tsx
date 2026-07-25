import AdministrationSection from './AdministrationSection';
import EnrollmentSection from './EnrollmentSection';
import ManagementSection from './ManagementSection';

type Props = {
  mode: 'enrollment' | 'management' | 'administration' | null;
};

export default function CourseOfferingSection({ mode }: Props) {
  switch (mode) {
    case 'enrollment':
      return <EnrollmentSection />;

    case 'management':
      return <ManagementSection />;

    case 'administration':
      return <AdministrationSection />;

    default:
      return null;
  }
}
