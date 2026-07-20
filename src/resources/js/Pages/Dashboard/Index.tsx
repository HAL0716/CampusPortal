import { Link, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';

import CourseOfferingTable from '@/Components/CourseOffering/List';
import { CourseOffering } from '@/Types/courseOffering';
import { SharedProps } from '@/Types/SharedProps';

interface Props extends SharedProps {
  courseOfferings: CourseOffering[];
}

export default function Index() {
  const { auth, courseOfferings } = usePage<Props>().props;

  return (
    <>
      <h1 className="mb-4 text-xl font-bold">ダッシュボード</h1>

      {auth.user && <p className="mb-4">ようこそ、{auth.user.name}さん！</p>}

      <CourseOfferingTable courseOfferings={courseOfferings} />

      <Link
        href={route('logout')}
        method="post"
        className="mt-4 bg-black px-4 py-2 text-white"
        as="button"
      >
        ログアウト
      </Link>
    </>
  );
}
