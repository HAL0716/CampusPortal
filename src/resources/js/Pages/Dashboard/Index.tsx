import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

export default function Index() {
  return (
    <>
      <h1 className="mb-4 text-xl font-bold">ダッシュボード</h1>

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
