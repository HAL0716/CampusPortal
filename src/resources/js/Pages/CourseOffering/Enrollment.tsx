import { Link, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';

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

  const enrolling = offering.status === null;

  const href = route(enrolling ? 'course-offerings.enroll' : 'course-offerings.drop', {
    courseOffering: offering.id,
  });

  const label = enrolling ? '履修登録' : '履修取消';

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

type FlashMessageProps = {
  type: 'success' | 'error';
  children: React.ReactNode;
};

function FlashMessage({ type, children }: FlashMessageProps) {
  const styles = {
    success: 'bg-green-100 text-green-800',
    error: 'bg-red-100 text-red-800',
  };

  return <div className={`mb-4 rounded p-4 ${styles[type]}`}>{children}</div>;
}

type pageProps = {
  offerings: EnrollmentCourseOffering[];
};

export default function Enrollment() {
  const { flash, offerings } = usePage<SharedProps & pageProps>().props;

  return (
    <>
      <h1 className="mb-4 text-xl font-bold">開講講義一覧</h1>

      {flash.success && <FlashMessage type="success">{flash.success}</FlashMessage>}

      {flash.error && <FlashMessage type="error">{flash.error}</FlashMessage>}

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
