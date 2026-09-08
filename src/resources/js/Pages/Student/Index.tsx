import { Head, usePage } from '@inertiajs/react';

import Card from '@/Components/Card';
import FlashMessage from '@/Components/FlashMessage';
import { SharedProps } from '@/Types/SharedProps';

type Student = {
  id: number;
  name: string;
  studentNumber: string;
  department: string;
};

type PageProps = {
  students: Student[];
};

export default function Index() {
  const { flash, students } = usePage<SharedProps & PageProps>().props;

  return (
    <>
      <Head title="学生一覧" />

      <h1 className="mb-6 text-xl font-bold">学生一覧</h1>

      <FlashMessage key={flash.success?.id} text={flash.success?.message} type="success" />

      <FlashMessage key={flash.error?.id} text={flash.error?.message} type="danger" />

      <div className="space-y-3">
        {students.map((student) => (
          <Card
            key={student.id}
            title={student.name}
            description={`学籍番号: ${student.studentNumber} | 学科: ${student.department}`}
          />
        ))}
      </div>
    </>
  );
}
