import { Link } from '@inertiajs/react';
import type { ReactNode } from 'react';

type CardVariant = 'default' | 'danger';

type Props = {
  href: string;
  title: string;
  description?: string | null;
  variant?: CardVariant;
  children?: ReactNode;
};

const variants: Record<
  CardVariant,
  {
    border: string;
    title: string;
    description: string;
    hover: string;
  }
> = {
  default: {
    border: 'border-gray-200',
    title: 'text-gray-900',
    description: 'text-gray-500',
    hover: 'hover:border-gray-400',
  },
  danger: {
    border: 'border-red-200',
    title: 'text-red-700',
    description: 'text-red-500',
    hover: 'hover:border-red-400',
  },
};

export default function Card({ href, title, description, variant = 'default', children }: Props) {
  const styles = variants[variant];

  return (
    <Link
      href={href}
      className={`block rounded-lg border bg-white p-5 shadow-sm transition hover:shadow-md ${styles.border} ${styles.hover}`}
    >
      <h2 className={`font-semibold ${styles.title}`}>{title}</h2>

      {description && <p className={`mt-2 text-sm ${styles.description}`}>{description}</p>}

      {children}
    </Link>
  );
}
