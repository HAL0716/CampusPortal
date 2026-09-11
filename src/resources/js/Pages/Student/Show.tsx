import { Head, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';

import Button from '@/Components/Button';
import Card from '@/Components/Card';
import FlashMessage from '@/Components/FlashMessage';
import { SharedProps } from '@/Types/SharedProps';

type Student = {
  id: number;
  name: string;
  studentNumber: string;
  department: string;
  credits: number;
  status: string;
  transitions: { value: string; label: string }[];
};

type PageProps = {
  student: Student;
};

export default function Show() {
  const { flash, student } = usePage<SharedProps & PageProps>().props;

  return (
    <>
      <Head title="学生の詳細" />

      <div className="space-y-6">
        <h1 className="text-2xl font-bold">{student.name}</h1>

        <FlashMessage key={flash.success?.id} text={flash.success?.message} type="success" />
        <FlashMessage key={flash.error?.id} text={flash.error?.message} type="danger" />

        <Card title="学籍番号" description={student.studentNumber} />
        <Card title="学科" description={student.department} />
        <Card title="修得単位数" description={student.credits.toString()} />
        <Card title="ステータス" description={student.status} />

        {student.transitions.map((status) => (
          <Button
            key={status.value}
            href={route('students.update.status', student.id)}
            method="patch"
            label={status.label}
            variant="danger"
            data={{ status: status.value, credits: student.credits }}
          />
        ))}
      </div>
    </>
  );
}
