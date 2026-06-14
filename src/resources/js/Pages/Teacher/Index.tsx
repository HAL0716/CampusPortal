import Table from '@/Components/Table/Table';
import PageTitle from '@/Components/Typography/PageTitle';

type Teacher = {
  id: number;
  login_id: string;
  name: string;
  email: string;
  department: string;
};

type Props = {
  teachers: Teacher[];
};

export default function Index({ teachers }: Props) {
  return (
    <div className="space-y-6">
      <PageTitle title="教員管理" />

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
