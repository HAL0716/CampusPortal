import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

import { CourseOffering } from '@/Types/CourseOffering';

type Props = {
  offering: CourseOffering;
};

export default function CourseOfferingAction({ offering }: Props) {
  if (offering.status === 'dropped') {
    return <span>取消済</span>;
  }

  if (offering.status === 'completed') {
    return <span>習得済</span>;
  }

  const enrolling = offering.status === null;

  return (
    <Link
      href={route(enrolling ? 'course-offerings.enroll' : 'course-offerings.drop', {
        courseOffering: offering.id,
      })}
      method="post"
      as="button"
      className={`rounded px-4 py-2 text-white ${
        enrolling ? 'bg-blue-600 hover:bg-blue-700' : 'bg-red-600 hover:bg-red-700'
      }`}
    >
      {enrolling ? '履修登録' : '履修取消'}
    </Link>
  );
}
