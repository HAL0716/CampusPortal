import { useForm, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';

import FlashMessage from '@/Components/FlashMessage';
import { ManagementCourseOffering, Student } from '@/Types/CourseOffering';
import { SharedProps } from '@/Types/SharedProps';

type ActionProps = {
  student: Student;
};

function Action({ student }: ActionProps) {
  const { data, setData, post, processing } = useForm({
    grade: '',
  });

  switch (student.status) {
    case 'dropped':
      return <span>取消済</span>;

    case 'completed':
      return <span>修得済</span>;
  }

  return (
    <div className="flex items-center gap-2">
      <select
        value={data.grade}
        onChange={(e) => setData('grade', e.target.value)}
        className="rounded border px-3 py-2"
      >
        <option value="" disabled>
          成績
        </option>
        <option value="S">S</option>
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="C">C</option>
        <option value="F">F</option>
      </select>

      <button
        type="button"
        disabled={!data.grade || processing}
        onClick={() =>
          post(
            route('enrollments.complete', {
              enrollment: student.id,
            }),
          )
        }
        className="rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700 disabled:opacity-50"
      >
        修得完了
      </button>
    </div>
  );
}

type PageProps = {
  offerings: ManagementCourseOffering[];
};

export default function Management() {
  const { flash, offerings } = usePage<SharedProps & PageProps>().props;

  return (
    <>
      <h1 className="mb-4 text-xl font-bold">担当講義一覧</h1>

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

      {offerings.map((offering) => (
        <section key={offering.id} className="mb-6">
          <h2 className="mb-2 text-lg font-semibold">{offering.name}</h2>

          <table className="w-full border-collapse border">
            <thead>
              <tr>
                <th className="border px-4 py-2 text-left">学籍番号</th>
                <th className="border px-4 py-2 text-left">操作</th>
              </tr>
            </thead>

            <tbody>
              {offering.students.map((student) => (
                <tr key={student.id}>
                  <td className="border px-4 py-2">{student.studentNumber}</td>
                  <td className="border px-4 py-2 text-center">
                    <Action student={student} />
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </section>
      ))}
    </>
  );
}
