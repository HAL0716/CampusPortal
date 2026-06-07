import { usePage } from '@inertiajs/react';
import { SharedProps } from '../../types/shared';
import Admin from './Admin';

type Role = 'admin' | 'teacher' | 'student';

type Props = SharedProps & {
  roles: {
    admin: Role;
    teacher: Role;
    student: Role;
  };
};

export default function Index() {
  const { user, roles } = usePage<Props>().props;

  switch (user?.role) {
    case roles.admin:
      return <Admin />;
    case roles.teacher:
      return <div>教員用Home(未実装)</div>;
    case roles.student:
      return <div>学生用Home(未実装)</div>;
    default:
      return <div>未考慮のロール</div>;
  }
}
