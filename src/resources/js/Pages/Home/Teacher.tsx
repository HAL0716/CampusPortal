import { Head } from '@inertiajs/react';
import { route } from 'ziggy-js';

import Menu from '@/Components/Menu';
import { MenuItem } from '@/Types/MenuItem';

export default function Teacher() {
  const menuItems: MenuItem[] = [
    {
      label: '科目一覧',
      href: route('courses.index'),
    },
    {
      label: '開講一覧',
      href: route('offerings.index'),
    },
    {
      label: 'ログアウト',
      href: route('logout'),
      method: 'post',
      className: 'text-red-500 underline hover:text-red-700',
    },
  ];

  return (
    <>
      <Head title="教員ページ" />

      <h1 className="mb-4 text-xl font-bold">教員ページ</h1>

      <Menu items={menuItems} />
    </>
  );
}
