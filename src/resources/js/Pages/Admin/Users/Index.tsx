import { usePage } from '@inertiajs/react';
import Student from './Student';
import Teacher from './Teacher';

type Role = 'student' | 'teacher';

type Props = {
  role: Role;
};

export default function Index() {
  const { role } = usePage<Props>().props;

  switch (role) {
    case 'student':
      return <Student />;
    case 'teacher':
      return <Teacher />;
    default:
      return <div>未考慮のロール</div>;
  }
}
