import { Link, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';

import FlashMessage from '@/Components/FlashMessage';
import { EnrollmentCourseOffering } from '@/Types/CourseOffering';
import { SharedProps } from '@/Types/SharedProps';

type ActionProps = {
  offering: EnrollmentCourseOffering;
};

const actionConfig = {
  enrolled: {
    route: 'course-offerings.drop',
    label: '取消',
    className: 'bg-red-600 hover:bg-red-700',
  },
  dropped: {
    route: 'course-offerings.enroll',
    label: '再登録',
    className: 'bg-blue-600 hover:bg-blue-700',
  },
  failed: {
    route: 'course-offerings.enroll',
    label: '再履修',
    className: 'bg-blue-600 hover:bg-blue-700',
  },
  default: {
    route: 'course-offerings.enroll',
    label: '登録',
    className: 'bg-blue-600 hover:bg-blue-700',
  },
} as const;

function Action({ offering }: ActionProps) {
  if (offering.status === 'completed') {
    return <span>修得済</span>;
  }

  const status = offering.status ?? 'default';
  const action = actionConfig[status];

  return (
    <Link
      href={route(action.route, { courseOffering: offering.id })}
      method="post"
      as="button"
      className={`rounded px-4 py-2 text-white ${action.className}`}
    >
      {action.label}
    </Link>
  );
}

type PageProps = {
  offerings: EnrollmentCourseOffering[];
};

export default function Enrollment() {
  const { flash, offerings } = usePage<SharedProps & PageProps>().props;

  return (
    <>
      <h1 className="mb-4 text-xl font-bold">開講講義一覧</h1>

      <FlashMessage key={flash.success?.id} text={flash.success?.message} type="success" />

      <FlashMessage key={flash.error?.id} text={flash.error?.message} type="danger" />

      <table className="mb-6 w-full border-collapse border">
        <thead>
          <tr>
            <th className="border px-4 py-2 text-left">講義名</th>
            <th className="border px-4 py-2">操作</th>
          </tr>
        </thead>

        <tbody>
          {offerings.map((offering) => (
            <tr key={offering.id}>
              <td className="border px-4 py-2">{offering.name}</td>
              <td className="border px-4 py-2 text-center">
                <Action offering={offering} />
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </>
  );
}
