import { Head, usePage } from '@inertiajs/react';

import Card from '@/Components/Card';

type Student = {
  id: number;
  name: string;
  studentNumber: string;
  department: string;
  credits: number;
};

type PageProps = {
  student: Student;
};

export default function Show() {
  const { student } = usePage<PageProps>().props;

  return (
    <>
      <Head title="学生の詳細" />

      <div className="space-y-6">
        <h1 className="text-2xl font-bold">{student.name}</h1>

        <Card title="学籍番号" description={student.studentNumber} />
        <Card title="学科" description={student.department} />
        <Card title="修得単位数" description={student.credits.toString()} />
      </div>
    </>
  );
}
