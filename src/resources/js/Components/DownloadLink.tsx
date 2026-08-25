import type { ReactNode } from 'react';

import { type Variant, variants } from '@/Components/Styles/Variants';

type Props = {
  href: string;
  label?: string;
  variant?: Variant;
  children?: ReactNode;
};

export default function DownloadLink({
  href,
  label = 'ダウンロード',
  variant = 'default',
  children,
}: Props) {
  const styles = variants[variant];

  const className = [
    'inline-flex items-center justify-center rounded-lg border',
    'bg-white px-5 py-3 font-semibold shadow-sm',
    'transition hover:shadow-md',
    styles.border,
    styles.text,
    styles.hover,
    styles.background,
  ].join(' ');

  return (
    <a href={href} className={className}>
      {children ?? label}
    </a>
  );
}
