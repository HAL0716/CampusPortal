import { Link, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';

import { ManagementCourseOffering } from '@/Types/CourseOffering';

type Props = {
  offerings: ManagementCourseOffering[];
};

export default function ManagementSection() {
  const { offerings } = usePage<Props>().props;

  return (
    <section>
      <h2 className="mb-2 text-lg font-semibold">担当科目一覧</h2>

      {offerings.map((offering) => (
        <div key={offering.id} className="mb-6">
          <h3 className="mb-2 font-medium">{offering.name}</h3>

          <table className="w-full border-collapse border">
            <thead>
              <tr>
                <th className="border px-4 py-2 text-left">学籍番号</th>
                <th className="border px-4 py-2 text-left">操作</th>
              </tr>
            </thead>

            <tbody>
              {offering.students.map((student) => (
                <tr key={student.studentId}>
                  <td className="border px-4 py-2">{student.studentNumber}</td>
                  <td className="border px-4 py-2 text-center">
                    <Link
                      href={route('enrollments.complete', { enrollment: student.enrollmentId })}
                      method="post"
                      as="button"
                      className="rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700"
                    >
                      修得完了
                    </Link>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      ))}
    </section>
  );
}
