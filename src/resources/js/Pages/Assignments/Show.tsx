import { Head } from '@inertiajs/react';

import { formatDate } from '@/Utils/date';

type Assignment = {
  id: number;
  title: string;
  description: string;
  file_path: string;
  due_date: string;
};

type Props = {
  assignment: Assignment;
};

export default function Show({ assignment }: Props) {
  return (
    <>
      <Head title={assignment.title} />

      <h1 className="mb-4 text-xl font-bold">{assignment.title}</h1>

      <section className="mb-6">
        <h2 className="font-bold">説明</h2>

        <p>{assignment.description}</p>
      </section>

      <section className="mb-6">
        <h2 className="font-bold">提出期限</h2>

        <p>{formatDate(assignment.due_date)}</p>
      </section>

      <section>
        <h2 className="font-bold">添付ファイル</h2>

        <p>{assignment.file_path}</p>
      </section>
    </>
  );
}
