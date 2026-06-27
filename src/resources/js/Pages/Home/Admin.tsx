import { Head } from '@inertiajs/react';
import { route } from 'ziggy-js';

import Menu from '@/Components/Menu';
import { MenuItem } from '@/Types/MenuItem';

export default function Admin() {
  const menuItems: MenuItem[] = [
    {
      label: '学生一覧',
      href: route('students.index'),
    },
    {
      label: '教員一覧',
      href: route('teachers.index'),
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
      <Head title="管理者ページ" />

      <h1 className="mb-4 text-xl font-bold">管理者ページ</h1>

      <Menu items={menuItems} />
    </>
  );
}
