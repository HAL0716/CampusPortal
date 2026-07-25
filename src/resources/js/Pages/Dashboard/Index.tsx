import { Link, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';

import { SharedProps } from '@/Types/SharedProps';

import CourseOfferingSection from './Components/CourseOfferingSection/Index';

type Props = {
  courseOfferingMode: 'enrollment' | 'management' | 'administration' | null;
};

type FlashMessageProps = {
  type: 'success' | 'error';
  children: React.ReactNode;
};

function FlashMessage({ type, children }: FlashMessageProps) {
  const styles = {
    success: 'bg-green-100 text-green-800',
    error: 'bg-red-100 text-red-800',
  };

  return <div className={`mb-4 rounded p-4 ${styles[type]}`}>{children}</div>;
}

export default function Index() {
  const { auth, flash, courseOfferingMode } = usePage<SharedProps & Props>().props;

  return (
    <>
      <h1 className="mb-4 text-xl font-bold">ダッシュボード</h1>

      {auth.user && <p className="mb-4">ようこそ、{auth.user.name}さん！</p>}

      {flash.success && <FlashMessage type="success">{flash.success}</FlashMessage>}

      {flash.error && <FlashMessage type="error">{flash.error}</FlashMessage>}

      <CourseOfferingSection mode={courseOfferingMode} />

      <Link
        href={route('logout')}
        method="post"
        as="button"
        className="bg-black px-4 py-2 text-white"
      >
        ログアウト
      </Link>
    </>
  );
}
