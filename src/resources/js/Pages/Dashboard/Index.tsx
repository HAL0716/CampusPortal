import { Link, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';

import { SharedProps } from '@/Types/SharedProps';

type Props = {
  courseOffering: {
    route: string;
    label: string;
  } | null;
};

export default function Index() {
  const { auth, courseOffering } = usePage<SharedProps & Props>().props;

  return (
    <>
      <h1 className="mb-4 text-xl font-bold">ダッシュボード</h1>

      {auth.user && <p className="mb-4">ようこそ、{auth.user.name}さん！</p>}

      <nav className="mb-6 flex flex-col gap-2">
        {courseOffering && (
          <Link
            href={route(courseOffering.route)}
            className="rounded-lg border border-gray-200 bg-white px-4 py-3 text-left transition hover:bg-gray-100"
          >
            {courseOffering.label}
          </Link>
        )}

        <Link
          href={route('logout')}
          method="post"
          as="button"
          className="rounded-lg border border-red-200 bg-red-100 px-4 py-3 text-left transition hover:bg-red-200"
        >
          ログアウト
        </Link>
      </nav>
    </>
  );
}
