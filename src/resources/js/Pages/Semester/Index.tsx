import { Head, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';

import Card from '@/Components/Card';
import FlashMessage from '@/Components/FlashMessage';
import { SharedProps } from '@/Types/SharedProps';

type Semester = {
  id: number;
  academicYear: string;
  term: string;
  startDate: string;
  endDate: string;
};

type PageProps = {
  semesters: Semester[];
};

export default function Index() {
  const { flash, semesters } = usePage<SharedProps & PageProps>().props;

  return (
    <>
      <Head title="学期一覧" />

      <h1 className="mb-6 text-xl font-bold">学期一覧</h1>

      <FlashMessage key={flash.success?.id} text={flash.success?.message} type="success" />

      <FlashMessage key={flash.error?.id} text={flash.error?.message} type="danger" />

      <div className="space-y-3">
        <Card key="追加" href={route('semesters.create')} title="追加" variant="info" />

        {semesters.map((semester) => (
          <Card
            key={semester.id}
            href={route('semesters.show', semester.id)}
            title={`${semester.academicYear} 年度 ${semester.term} 学期`}
            description={`${semester.startDate} ~ ${semester.endDate}`}
          />
        ))}
      </div>
    </>
  );
}
