import type { InputHTMLAttributes } from 'react';

import FieldError from '@/Components/Form/FieldError';
import Label from '@/Components/Form/Label';

type Props = {
  id: string;
  label: string;
  error?: string;
  value: string;
  onChange: (value: string) => void;
} & Omit<InputHTMLAttributes<HTMLInputElement>, 'id' | 'value' | 'onChange'>;

export default function Input({ id, label, error, value, onChange, ...props }: Props) {
  const errorId = `${id}-error`;

  return (
    <div>
      <Label htmlFor={id}>{label}</Label>

      <input
        {...props}
        id={id}
        value={value}
        onChange={(e) => onChange(e.target.value)}
        aria-invalid={!!error}
        aria-describedby={error ? errorId : undefined}
        className="w-full rounded-md border border-gray-200 bg-gray-50 px-3 py-2 transition outline-none focus:border-gray-400 focus:bg-white focus:ring-2 focus:ring-gray-100"
      />

      <FieldError id={errorId} error={error} />
    </div>
  );
}
