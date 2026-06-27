import { Head } from '@inertiajs/react';

import Table from '@/Components/Table';

type Enrollment = {
  id: number;
  course: string;
  teacher: string;
  day_of_week: string;
  period: string;
  status: string;
};

type Props = {
  enrollments: Enrollment[];
};

export default function Index({ enrollments }: Props) {
  const columns = [
    {
      label: '科目',
      key: 'course',
    },
    {
      label: '担当教員',
      key: 'teacher',
    },
    {
      label: '曜日',
      key: 'day_of_week',
    },
    {
      label: '時限',
      key: 'period',
    },
    {
      label: '状態',
      key: 'status',
    },
  ];

  return (
    <>
      <Head title="履修科目" />

      <h1 className="mb-4 text-xl font-bold">履修中の科目</h1>
      <Table data={enrollments} columns={columns} />
    </>
  );
}
