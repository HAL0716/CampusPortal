import { Head } from '@inertiajs/react';

import Table from '@/Components/Table';

type Course = {
  id: number;
  name: string;
  department: string;
  term: string;
  credits: number;
  teacher: string | null;
};

type Props = {
  courses: Course[];
};

export default function Index({ courses }: Props) {
  const columns = [
    {
      label: '科目名',
      key: 'name',
    },
    {
      label: '学科',
      key: 'department',
    },
    {
      label: '学期',
      key: 'term',
    },
    {
      label: '単位',
      key: 'credits',
    },
    {
      label: '担当教員',
      key: 'teacher',
    },
  ];

  return (
    <>
      <Head title="科目一覧" />

      <h1 className="mb-4 text-xl font-bold">科目一覧</h1>
      <Table data={courses} columns={columns} />
    </>
  );
}
