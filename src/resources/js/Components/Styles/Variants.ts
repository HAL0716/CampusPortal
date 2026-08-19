export type Variant = 'default' | 'info' | 'danger' | 'success' | 'warning' | 'accent';

export const variants: Record<
  Variant,
  {
    border: string;
    text: string;
    description: string;
    hover: string;
    background: string;
  }
> = {
  default: {
    border: 'border-gray-200',
    text: 'text-gray-900',
    description: 'text-gray-500',
    hover: 'hover:border-gray-400',
    background: 'bg-gray-100',
  },
  info: {
    border: 'border-blue-200',
    text: 'text-blue-700',
    description: 'text-blue-500',
    hover: 'hover:border-blue-400',
    background: 'bg-blue-100',
  },
  danger: {
    border: 'border-red-200',
    text: 'text-red-700',
    description: 'text-red-500',
    hover: 'hover:border-red-400',
    background: 'bg-red-100',
  },
  success: {
    border: 'border-green-200',
    text: 'text-green-700',
    description: 'text-green-500',
    hover: 'hover:border-green-400',
    background: 'bg-green-100',
  },
  warning: {
    border: 'border-orange-200',
    text: 'text-orange-700',
    description: 'text-orange-500',
    hover: 'hover:border-orange-400',
    background: 'bg-orange-100',
  },
  accent: {
    border: 'border-purple-200',
    text: 'text-purple-700',
    description: 'text-purple-500',
    hover: 'hover:border-purple-400',
    background: 'bg-purple-100',
  },
};
