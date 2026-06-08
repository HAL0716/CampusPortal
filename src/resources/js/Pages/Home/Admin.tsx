import MenuCard from '../Components/MenuCard';
import PageTitle from '../Components/PageTitle';

export default function Admin() {
  const menus = [
    {
      title: '学生管理',
      description: '学生アカウントの作成・更新を行います。',
      href: 'admin/users',
    },
    {
      title: '教員管理',
      description: '教員アカウントの作成を行います。',
      href: 'admin/users?role=teacher',
    },
  ];

  return (
    <div className="space-y-6">
      <PageTitle title="管理者ページ" />

      <div className="grid gap-4">
        {menus.map((menu) => (
          <MenuCard
            key={menu.title}
            title={menu.title}
            description={menu.description}
            href={menu.href}
          />
        ))}
      </div>
    </div>
  );
}
