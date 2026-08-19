import { Link } from '@inertiajs/react';
import type { ReactNode } from 'react';

import { type Variant, variants } from '@/Components/Styles/Variants';

type Props = {
  href?: string;
  title?: string;
  description?: string | null;
  variant?: Variant;
  children?: ReactNode;
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
      {title && <h2 className={`font-semibold ${styles.text}`}>{title}</h2>}

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
