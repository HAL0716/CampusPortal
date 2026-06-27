import { Head } from '@inertiajs/react';

import Table from '@/Components/Table';

type Student = {
  id: number;
  student_no: string;
  name: string;
  department: string;
  degree: string;
  year: number;
};

type Props = {
  students: Student[];
};

export default function Index({ students }: Props) {
  const columns = [
    {
      label: '学籍番号',
      key: 'student_no',
    },
    {
      label: '氏名',
      key: 'name',
    },
    {
      label: '学科',
      key: 'department',
    },
    {
      label: '課程',
      key: 'degree',
    },
    {
      label: '学年',
      key: 'year',
      render: (student: Student) => `${student.year}年`,
    },
  ];

  return (
    <>
      <Head title="学生一覧" />

      <h1 className="mb-4 text-xl font-bold">学生一覧</h1>
      <Table data={students} columns={columns} />
    </>
  );
}
