import { Head, Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

import { SharedProps } from '@/Types/ShareProps';

export default function Index({ auth }: SharedProps) {
  return (
    <>
      <Head title="ホームページ" />

      <div className="p-6">
        <h1 className="mb-4 text-xl font-bold">
          {auth.user ? auth.user.name : 'ゲスト'} さん用ホームページ
        </h1>

        <Link
          href={route('logout')}
          method="post"
          className="mt-6 rounded bg-blue-500 p-2 text-center text-white hover:bg-blue-600"
        >
          ログアウト
        </Link>
      </div>
    </>
  );
}
