import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

import { SharedProps } from '@/Types/ShareProps';

export default function Index({ auth }: SharedProps) {
  return (
    <div className="flex min-h-screen items-center justify-center bg-gray-100">
      <div className="w-full max-w-md rounded-2xl bg-white p-8 shadow">
        <h1 className="mb-6 text-center text-2xl font-bold">ホームページ</h1>

        <p className="text-center text-gray-600">
          ようこそ、{auth.user ? auth.user.name : 'ゲスト'} さん！
        </p>

        <Link
          href={route('logout')}
          method="post"
          className="mt-6 block w-full rounded bg-blue-500 px-4 py-2 text-center text-white hover:bg-blue-600"
        >
          ログアウト
        </Link>
      </div>
    </div>
  );
}
