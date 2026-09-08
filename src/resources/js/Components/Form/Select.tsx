import type { SelectHTMLAttributes } from 'react';

import FieldError from '@/Components/Form/FieldError';
import Label from '@/Components/Form/Label';

type Option = {
  value: string;
  label: string;
};

type Props = {
  id: string;
  label: string;
  value: string;
  options: Option[];
  onChange: (value: string) => void;
  placeholder?: string;
  error?: string;
} & Omit<SelectHTMLAttributes<HTMLSelectElement>, 'id' | 'value' | 'onChange'>;

export default function Select({
  id,
  label,
  value,
  options,
  onChange,
  placeholder,
  error,
  ...props
}: Props) {
  const errorId = `${id}-error`;

  return (
    <div>
      <Label htmlFor={id}>{label}</Label>

      <select
        {...props}
        id={id}
        value={value}
        onChange={(event) => onChange(event.target.value)}
        aria-invalid={Boolean(error)}
        aria-describedby={error ? errorId : undefined}
        className="w-full rounded-md border border-gray-200 bg-gray-50 px-3 py-2 transition outline-none focus:border-gray-400 focus:bg-white focus:ring-2 focus:ring-gray-100"
      >
        {placeholder && <option value="">{placeholder}</option>}

        {options.map(({ value, label }) => (
          <option key={value} value={value}>
            {label}
          </option>
        ))}
      </select>

      <FieldError id={errorId} error={error} />
    </div>
  );
}
