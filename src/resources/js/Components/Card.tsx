import { Link } from '@inertiajs/react';
import type { ReactNode } from 'react';

type CardVariant = 'default' | 'info' | 'danger' | 'success' | 'warning' | 'accent';

type Props = {
  href?: string;
  title?: string;
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
  info: {
    border: 'border-blue-200',
    title: 'text-blue-700',
    description: 'text-blue-500',
    hover: 'hover:border-blue-400',
  },
  danger: {
    border: 'border-red-200',
    title: 'text-red-700',
    description: 'text-red-500',
    hover: 'hover:border-red-400',
  },
  success: {
    border: 'border-green-200',
    title: 'text-green-700',
    description: 'text-green-500',
    hover: 'hover:border-green-400',
  },
  warning: {
    border: 'border-orange-200',
    title: 'text-orange-700',
    description: 'text-orange-500',
    hover: 'hover:border-orange-400',
  },
  accent: {
    border: 'border-purple-200',
    title: 'text-purple-700',
    description: 'text-purple-500',
    hover: 'hover:border-purple-400',
  },
};

export default function Card({ href, title, description, variant = 'default', children }: Props) {
  const styles = variants[variant];

  const className = [
    'rounded-lg border bg-white p-5 shadow-sm',
    styles.border,
    href && 'transition hover:shadow-md',
    href && styles.hover,
  ]
    .filter(Boolean)
    .join(' ');

  const content = (
    <>
      {title && <h2 className={`font-semibold ${styles.title}`}>{title}</h2>}

      {description && <p className={`mt-2 text-sm ${styles.description}`}>{description}</p>}

      {children}
    </>
  );

  if (href) {
    return (
      <Link href={href} className={`block ${className}`}>
        {content}
      </Link>
    );
  }

  return <div className={className}>{content}</div>;
}
