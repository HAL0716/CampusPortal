import { Head } from '@inertiajs/react';

import Table from '@/Components/Table';

type Teacher = {
  id: number;
  name: string;
  email: string;
  department: string;
};

type Props = {
  teachers: Teacher[];
};

export default function Index({ teachers }: Props) {
  const columns = [
    {
      label: '氏名',
      key: 'name',
    },
    {
      label: 'メールアドレス',
      key: 'email',
    },
    {
      label: '学科',
      key: 'department',
    },
  ];

  return (
    <>
      <Head title="教員一覧" />

      <h1 className="mb-4 text-xl font-bold">教員一覧</h1>
      <Table data={teachers} columns={columns} />
    </>
  );
}
