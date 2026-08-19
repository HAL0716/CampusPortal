import { useForm, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';

import FlashMessage from '@/Components/FlashMessage';
import { Enrollment, ManagementCourseOffering } from '@/Types/CourseOffering';
import { SharedProps } from '@/Types/SharedProps';

type ActionProps = {
  enrollment: Enrollment;
};

function Action({ enrollment }: ActionProps) {
  const { data, setData, post, processing } = useForm({
    grade: '',
  });

  switch (enrollment.status) {
    case 'dropped':
      return <span>取消済</span>;

    case 'completed':
      return <span>修得済</span>;

    case 'failed':
      return <span>不合格</span>;
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
              enrollment: enrollment.id,
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

      <FlashMessage key={flash.success?.id} text={flash.success?.message} type="success" />

      <FlashMessage key={flash.error?.id} text={flash.error?.message} type="danger" />

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
              {offering.enrollments.map((enrollment) => (
                <tr key={enrollment.id}>
                  <td className="border px-4 py-2">{enrollment.studentNumber}</td>
                  <td className="border px-4 py-2 text-center">
                    <Action enrollment={enrollment} />
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
