import { Head } from '@inertiajs/react';

import Table from '@/Components/Table';

type AcademicTerm = {
  id: number;
  academic_year: number;
  term: string;
  registration_start: string;
  registration_end: string;
  lecture_start: string;
  lecture_end: string;
};

type Props = {
  academicTerms: AcademicTerm[];
};

export default function Index({ academicTerms }: Props) {
  const format = (date: string) => new Date(date).toLocaleDateString('ja-JP');

  const columns = [
    {
      label: '年度',
      key: 'academic_year',
    },
    {
      label: '学期',
      key: 'term',
    },
    {
      label: '履修登録期間',
      key: 'registration',
      render: (row: AcademicTerm) =>
        `${format(row.registration_start)} 〜 ${format(row.registration_end)}`,
    },
    {
      label: '講義期間',
      key: 'lecture',
      render: (row: AcademicTerm) => `${format(row.lecture_start)} 〜 ${format(row.lecture_end)}`,
    },
  ];

  return (
    <>
      <Head title="学期一覧" />

      <h1 className="mb-4 text-xl font-bold">学期一覧</h1>

      <Table data={academicTerms} columns={columns} />
    </>
  );
}
