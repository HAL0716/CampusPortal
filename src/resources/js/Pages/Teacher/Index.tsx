import { Link, router, usePage } from '@inertiajs/react';
import { useEffect } from 'react';

import Table from '@/Components/Table/Table';
import PageTitle from '@/Components/Typography/PageTitle';
import SectionTitle from '@/Components/Typography/SectionTitle';
import Button from '@/Components/UI/Button';
import useToast from '@/Hooks/useToast';
import { SharedProps } from '@/Types/SharedProps';
import { copyToClipboard } from '@/Utils/clipboard';

type Teacher = {
  id: number;
  login_id: string;
  name: string;
  email: string;
  department: string;
  is_active: boolean;
};

type Props = {
  teachers: Teacher[];
  generated_password?: string;
};

export default function Index({ teachers, generated_password }: Props) {
  const toast = useToast();

  const { flash } = usePage<SharedProps>().props;

  useEffect(() => {
    if (flash?.success) {
      toast.success(flash.success);
    }

    if (flash?.error) {
      toast.error(flash.error);
    }
  }, [flash, toast]);

  return (
    <div className="space-y-6">
      <PageTitle title="教員管理" />

      <SectionTitle title="教員追加" />

      <div className="flex flex-wrap gap-3">
        <Link
          href="/teachers/appoint"
          className="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 font-medium text-white transition hover:bg-blue-700"
        >
          教員着任
        </Link>

        {generated_password && (
          <div className="rounded-lg border border-green-200 bg-green-50 p-4">
            <p className="text-sm text-slate-600">発行された初期パスワード</p>

            <div className="mt-2 flex items-center gap-4">
              <code className="font-mono text-lg">{generated_password}</code>

              <Button
                onClick={() =>
                  copyToClipboard(generated_password, {
                    onSuccess: () => toast.success('コピーに成功'),
                    onError: () => toast.error('コピーに失敗'),
                  })
                }
              >
                コピー
              </Button>
            </div>
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
          {
            id: 'actions',
            key: 'id',
            label: '操作',
            render: (teacher) =>
              teacher.is_active ? (
                <Button
                  onClick={() => {
                    if (!confirm('この教員を退任させますか？')) return;

                    router.patch(`/teachers/${teacher.id}/resign`);
                  }}
                  className="bg-red-600 hover:bg-red-700"
                >
                  退任
                </Button>
              ) : (
                <span className="text-gray-500">退任済み</span>
              ),
          },
        ]}
        emptyMessage="教員が見つかりませんでした。"
      />
    </div>
  );
}
