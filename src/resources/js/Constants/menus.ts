export type MenuItem = {
  title: string;
  description: string;
  href: string;
};

type MenuItems = {
  admin: MenuItem[];
  teacher: MenuItem[];
  student: MenuItem[];
};

export const menuItems: MenuItems = {
  admin: [
    {
      title: '教員管理',
      description: '教員の着任・退任を管理します。',
      href: '/teachers',
    },
  ],
  teacher: [],
  student: [],
} as const;
