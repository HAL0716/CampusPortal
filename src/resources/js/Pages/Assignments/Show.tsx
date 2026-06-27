import { Head } from '@inertiajs/react';

import Table from '@/Components/Table';
import { formatDate } from '@/Utils/date';

type Submission = {
  id: number;
  file_path: string;
  submitted_at: string;
  score: number | null;
};

type Assignment = {
  id: number;
  title: string;
  description: string;
  file_path: string;
  due_date: string;
  submissions: Submission[];
};

type Props = {
  assignment: Assignment;
};

export default function Show({ assignment }: Props) {
  const columns = [
    {
      label: 'ファイル',
      key: 'file_path',
    },
    {
      label: '提出日時',
      key: 'submitted_at',
      render: (submission: Submission) => formatDate(submission.submitted_at),
    },
    {
      label: '点数',
      key: 'score',
      render: (submission: Submission) =>
        submission.score !== null ? `${submission.score}点` : '未採点',
    },
  ];

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

      <section className="mb-6">
        <h2 className="font-bold">添付ファイル</h2>

        <p>{assignment.file_path}</p>
      </section>

      <section className="mb-6">
        <h2 className="font-bold">提出状況</h2>

        {assignment.submissions.length > 0 ? (
          <Table data={assignment.submissions} columns={columns} />
        ) : (
          <p>未提出</p>
        )}
      </section>
    </>
  );
}
