import { Head, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';

import Card from '@/Components/Card';

type courseOffering = {
  id: number;
  name: string;
  description: string;
};

type Semester = {
  id: number;
  academicYear: string;
  term: string;
  startDate: string;
  endDate: string;
  courseOfferings: courseOffering[];
};

export default function Show() {
  const { semester } = usePage<{ semester: Semester }>().props;

  return (
    <>
      <Head title="学期詳細" />

      <h1 className="mb-6 text-xl font-bold">
        {semester.academicYear} 年度 {semester.term} 学期 開講科目
      </h1>

      {semester.courseOfferings.length > 0 ? (
        <div className="space-y-3">
          {semester.courseOfferings.map((offering) => (
            <Card
              key={offering.id}
              href={route('course-offerings.show', offering.id)}
              title={offering.name}
              description={offering.description}
            />
          ))}
        </div>
      ) : (
        <p className="text-gray-500">開講科目がありません。</p>
      )}
    </>
  );
}
