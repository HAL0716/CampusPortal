import { Head } from '@inertiajs/react';

import Table from '@/Components/Table';

type Grade = {
  course: string;
  grade: string | null;
};

type Props = {
  grades: Grade[];
};

export default function Index({ grades }: Props) {
  const columns = [
    {
      label: '科目',
      key: 'course',
    },
    {
      label: '成績',
      key: 'grade',
    },
  ];

  return (
    <>
      <Head title="成績一覧" />

      <h1 className="mb-4 text-xl font-bold">成績一覧</h1>
      <Table data={grades} columns={columns} />
    </>
  );
}
