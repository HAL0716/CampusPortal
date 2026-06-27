import { Link } from '@inertiajs/react';

import { MenuItem } from '@/Types/MenuItem';

type Props = {
  items: MenuItem[];
};

export default function Menu({ items }: Props) {
  return (
    <div className="flex flex-col items-start gap-4">
      {items.map((item) => (
        <Link
          key={item.label}
          href={item.href}
          method={item.method}
          className={item.className ?? 'text-blue-500 underline hover:text-blue-700'}
        >
          {item.label}
        </Link>
      ))}
    </div>
  );
}
