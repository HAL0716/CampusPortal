import { Link, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';

import { EnrollmentCourseOffering } from '@/Types/CourseOffering';

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

type pageProps = {
  offerings: EnrollmentCourseOffering[];
};

export default function EnrollmentSection() {
  const { offerings } = usePage<pageProps>().props;

  return (
    <section>
      <h2 className="mb-2 text-lg font-semibold">開講科目一覧</h2>

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
    </section>
  );
}
