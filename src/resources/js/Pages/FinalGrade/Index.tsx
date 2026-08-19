import { Head, useForm, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';

import Button from '@/Components/Button';
import Card from '@/Components/Card';

type Grade = 'F' | 'C' | 'B' | 'A' | 'S';

type PageProps = {
  enrollments: {
    enrollmentId: number;
    studentNumber: string;
    finalGrade: Grade | null;
  }[];
};

const grades: Grade[] = ['F', 'C', 'B', 'A', 'S'];

function GradeForm({ enrollment }: { enrollment: PageProps['enrollments'][number] }) {
  const { data, setData, post } = useForm({
    grade: '' as Grade | '',
  });

  if (enrollment.finalGrade) {
    return (
      <div className="text-right">
        <span className="text-sm text-gray-500">最終成績</span>
        <p className="text-2xl font-bold text-gray-900">{enrollment.finalGrade}</p>
      </div>
    );
  }

  return (
    <form
      onSubmit={(e) => {
        e.preventDefault();

        if (!data.grade) {
          return;
        }

        post(
          route('enrollments.complete', {
            enrollment: enrollment.enrollmentId,
          }),
        );
      }}
      className="flex items-center gap-3"
    >
      <select
        value={data.grade}
        onChange={(e) => setData('grade', e.target.value as Grade)}
        className="rounded-md border border-gray-200 bg-gray-50 px-3 py-2 transition outline-none focus:border-gray-400 focus:bg-white focus:ring-2 focus:ring-gray-100"
      >
        <option value="">成績を選択</option>

        {grades.map((grade) => (
          <option key={grade} value={grade}>
            {grade}
          </option>
        ))}
      </select>

      <Button type="submit" variant="success">
        保存
      </Button>
    </form>
  );
}

export default function Index() {
  const { enrollments } = usePage<PageProps>().props;

  return (
    <>
      <Head title="最終成績" />

      <h1 className="mb-6 text-xl font-bold">最終成績</h1>

      <div className="space-y-3">
        {enrollments.map((enrollment) => (
          <Card key={enrollment.enrollmentId} title={enrollment.studentNumber}>
            <div className="mt-3 flex items-center justify-between">
              <GradeForm enrollment={enrollment} />
            </div>
          </Card>
        ))}
      </div>
    </>
  );
}
