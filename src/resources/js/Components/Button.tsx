import { Link } from '@inertiajs/react';

type ButtonVariant = 'default' | 'info' | 'danger' | 'success' | 'warning' | 'accent';

type Props = {
  href: string;
  label: string;
  variant?: ButtonVariant;
};

const variants: Record<
  ButtonVariant,
  {
    border: string;
    text: string;
    hover: string;
  }
> = {
  default: {
    border: 'border-gray-200',
    text: 'text-gray-900',
    hover: 'hover:border-gray-400 hover:bg-gray-50',
  },
  info: {
    border: 'border-blue-200',
    text: 'text-blue-700',
    hover: 'hover:border-blue-400 hover:bg-blue-50',
  },
  danger: {
    border: 'border-red-200',
    text: 'text-red-700',
    hover: 'hover:border-red-400 hover:bg-red-50',
  },
  success: {
    border: 'border-green-200',
    text: 'text-green-700',
    hover: 'hover:border-green-400 hover:bg-green-50',
  },
  warning: {
    border: 'border-orange-200',
    text: 'text-orange-700',
    hover: 'hover:border-orange-400 hover:bg-orange-50',
  },
  accent: {
    border: 'border-purple-200',
    text: 'text-purple-700',
    hover: 'hover:border-purple-400 hover:bg-purple-50',
  },
};

export default function Button({ href, label, variant = 'default' }: Props) {
  const styles = variants[variant];

  return (
    <Link
      href={href}
      method="post"
      as="button"
      className={[
        'w-full rounded-lg border bg-white px-5 py-4',
        'text-left font-semibold shadow-sm',
        'transition hover:shadow-md',
        styles.border,
        styles.text,
        styles.hover,
      ].join(' ')}
    >
      {label}
    </Link>
  );
}
