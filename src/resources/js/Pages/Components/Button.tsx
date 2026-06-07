import { ButtonHTMLAttributes } from 'react';

type Variant = 'primary' | 'secondary' | 'success' | 'danger' | 'warning';

type Props = ButtonHTMLAttributes<HTMLButtonElement> & {
  loading?: boolean;
  label?: string;
  variant?: Variant;
  fullWidth?: boolean;
};

export default function Button({
  label = 'Submit',
  loading = false,
  disabled,
  variant = 'primary',
  fullWidth = true,
  className = '',
  ...props
}: Props) {
  const variants: Record<Variant, string> = {
    primary: 'bg-blue-600 hover:bg-blue-700 text-white',
    secondary: 'bg-gray-600 hover:bg-gray-700 text-white',
    success: 'bg-green-600 hover:bg-green-700 text-white',
    danger: 'bg-red-600 hover:bg-red-700 text-white',
    warning: 'bg-yellow-500 hover:bg-yellow-600 text-white',
  };

  return (
    <button
      type="submit"
      disabled={loading || disabled}
      className={`rounded px-4 py-2 transition disabled:cursor-not-allowed disabled:opacity-50 ${fullWidth ? 'w-full' : ''} ${variants[variant]} ${className} `}
      {...props}
    >
      {loading ? '処理中...' : label}
    </button>
  );
}
