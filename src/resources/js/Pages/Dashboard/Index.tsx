import { Head, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';

import Button from '@/Components/Button';
import Card from '@/Components/Card';
import { SharedProps } from '@/Types/SharedProps';

type PageProps = {
  canManageStudents: boolean;
};

export default function Index() {
  const { auth, canManageStudents } = usePage<SharedProps & PageProps>().props;

  return (
    <>
      <Head title="ダッシュボード" />

      <h1 className="mb-4 text-xl font-bold">
        {auth.user && `${auth.user.name} の`}ダッシュボード
      </h1>

      <nav className="mb-6 flex flex-col gap-2">
        <Card
          href={route('course-offerings.index')}
          title="開講科目"
          description="開講科目の一覧を表示します。"
        />

        {canManageStudents && (
          <Card
            href={route('students.index')}
            title="学生管理"
            description="学生情報の管理を行います。"
          />
        )}

        <Button href={route('logout')} label="ログアウト" variant="danger" />
      </nav>
    </>
  );
}
