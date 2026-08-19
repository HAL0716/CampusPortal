import { Link } from '@inertiajs/react';
import type { ButtonHTMLAttributes, ReactNode } from 'react';

import { type Variant, variants } from '@/Components/Styles/Variants';

type Props = {
  href?: string;
  label?: string;
  variant?: Variant;
  type?: ButtonHTMLAttributes<HTMLButtonElement>['type'];
  children?: ReactNode;
};

export default function Button({
  href,
  label,
  variant = 'default',
  type = 'button',
  children,
}: Props) {
  const styles = variants[variant];

  const className = [
    'w-full rounded-lg border bg-white px-5 py-4',
    'font-semibold shadow-sm',
    'transition hover:shadow-md',
    styles.border,
    styles.text,
    styles.hover,
    styles.background,
  ].join(' ');

  const content = children ?? label;

  if (href) {
    return (
      <Link href={href} method="post" as="button" className={className}>
        {content}
      </Link>
    );
  }

  return (
    <button type={type} className={className}>
      {content}
    </button>
  );
}
