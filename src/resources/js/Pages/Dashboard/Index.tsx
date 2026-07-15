import { Link, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';

import { SharedProps } from '@/Types/SharedProps';

export default function Index() {
  const { auth } = usePage<SharedProps>().props;

  return (
    <>
      <h1 className="mb-4 text-xl font-bold">ダッシュボード</h1>

      {auth.user && <p className="mb-4">ようこそ、{auth.user.name}さん！</p>}

      <Link
        href={route('logout')}
        method="post"
        className="bg-black px-4 py-2 text-white"
        as="button"
      >
        ログアウト
      </Link>
    </>
  );
}
