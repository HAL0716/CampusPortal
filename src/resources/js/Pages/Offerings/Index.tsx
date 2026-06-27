import { Head } from '@inertiajs/react';

import Table from '@/Components/Table';

type Offering = {
  id: number;
  course: string;
  teacher: string;
  day_of_week: string;
  period: string;
};

type Props = {
  offerings: Offering[];
};

export default function Index({ offerings }: Props) {
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
  ];

  return (
    <>
      <Head title="開講科目一覧" />

      <h1 className="mb-4 text-xl font-bold">開講一覧</h1>
      <Table data={offerings} columns={columns} />
    </>
  );
}
