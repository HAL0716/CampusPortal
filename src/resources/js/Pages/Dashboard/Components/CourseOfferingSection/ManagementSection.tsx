import { usePage } from '@inertiajs/react';

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
              </tr>
            </thead>

            <tbody>
              {offering.students.map((student) => (
                <tr key={student.id}>
                  <td className="border px-4 py-2">{student.studentNumber}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      ))}
    </section>
  );
}
