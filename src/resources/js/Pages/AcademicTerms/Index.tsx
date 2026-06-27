import { Head } from '@inertiajs/react';

import Table from '@/Components/Table';
import { formatDate } from '@/Utils/date';

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
        `${formatDate(row.registration_start)} 〜 ${formatDate(row.registration_end)}`,
    },
    {
      label: '講義期間',
      key: 'lecture',
      render: (row: AcademicTerm) =>
        `${formatDate(row.lecture_start)} 〜 ${formatDate(row.lecture_end)}`,
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
