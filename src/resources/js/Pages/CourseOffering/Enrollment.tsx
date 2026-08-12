import { Link, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';

import FlashMessage from '@/Components/FlashMessage';
import { EnrollmentCourseOffering } from '@/Types/CourseOffering';
import { SharedProps } from '@/Types/SharedProps';

type ActionProps = {
  offering: EnrollmentCourseOffering;
};

function Action({ offering }: ActionProps) {
  switch (offering.status) {
    case 'dropped':
      return <span>取消済</span>;

    case 'completed':
      return <span>修得済</span>;
  }

  const enrolling = offering.status === null || offering.status === 'failed';

  const href = route(enrolling ? 'course-offerings.enroll' : 'course-offerings.drop', {
    courseOffering: offering.id,
  });

  const label = enrolling ? (offering.status === 'failed' ? '再履修' : '履修登録') : '履修取消';

  const buttonClass = enrolling ? 'bg-blue-600 hover:bg-blue-700' : 'bg-red-600 hover:bg-red-700';

  return (
    <Link
      href={href}
      method="post"
      as="button"
      className={`rounded px-4 py-2 text-white ${buttonClass}`}
    >
      {label}
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

      {flash.success && (
        <FlashMessage key={flash.success.id} type="success">
          {flash.success.message}
        </FlashMessage>
      )}

      {flash.error && (
        <FlashMessage key={flash.error.id} type="error">
          {flash.error.message}
        </FlashMessage>
      )}

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
