import { Link } from '@inertiajs/react';

import Table from '@/Components/Table/Table';
import PageTitle from '@/Components/Typography/PageTitle';
import SectionTitle from '@/Components/Typography/SectionTitle';

type Teacher = {
  id: number;
  login_id: string;
  name: string;
  email: string;
  department: string;
};

type Props = {
  teachers: Teacher[];
  generated_password?: string;
};

export default function Index({ teachers, generated_password }: Props) {
  return (
    <div className="space-y-6">
      <PageTitle title="教員管理" />

      <SectionTitle title="教員追加" />

      <div className="flex items-center justify-between">
        <Link href="/teachers/appoint" className="rounded-lg bg-blue-600 px-4 py-2 text-white">
          教員着任
        </Link>

        {generated_password && (
          <div className="rounded bg-green-100 p-2 text-green-800">
            生成されたパスワード: {generated_password}
          </div>
        )}
      </div>

      <SectionTitle title="教員一覧" />

      <Table
        data={teachers}
        columns={[
          { id: 'login_id', key: 'login_id', label: 'ログインID' },
          { id: 'name', key: 'name', label: '氏名' },
          { id: 'email', key: 'email', label: 'メールアドレス' },
          { id: 'department', key: 'department', label: '所属' },
        ]}
        emptyMessage="教員が見つかりませんでした。"
      />
    </div>
  );
}
