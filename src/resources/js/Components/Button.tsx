import { Link } from '@inertiajs/react';

import { type Variant, variants } from '@/Components/Styles/Variants';

type Props = {
  href: string;
  label: string;
  variant?: Variant;
};

export default function Button({ href, label, variant = 'default' }: Props) {
  const styles = variants[variant];

  const className = [
    'w-full rounded-lg border bg-white px-5 py-4',
    'text-left font-semibold shadow-sm',
    'transition hover:shadow-md',
    styles.border,
    styles.text,
    styles.hover,
    styles.background,
  ].join(' ');

  return (
    <Link href={href} method="post" as="button" className={className}>
      {label}
    </Link>
  );
}
