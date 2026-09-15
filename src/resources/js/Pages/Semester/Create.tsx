import { Head, usePage } from '@inertiajs/react';

import Card from '@/Components/Card';
import CreateForm from '@/Components/Semester/CreateForm';

type PageProps = {
  latestSemester: {
    id: number;
    academicYear: string;
    term: number;
    startDate: string;
    endDate: string;
  };
};

export default function Create() {
  const { latestSemester } = usePage<PageProps>().props;

  return (
    <>
      <Head title="学期登録" />

      <h1 className="mb-6 text-2xl font-bold">学期登録</h1>

      <Card
        key={latestSemester.id}
        title={`直前：${latestSemester.academicYear} 年度 ${latestSemester.term} 学期`}
        description={`${latestSemester.startDate} ~ ${latestSemester.endDate}`}
      />

      <CreateForm endDate={latestSemester.endDate} />
    </>
  );
}
