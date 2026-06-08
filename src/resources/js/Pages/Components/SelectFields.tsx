import { SelectHTMLAttributes } from 'react';

type Option = {
  value: string | number;
  label: string;
};

type Props = SelectHTMLAttributes<HTMLSelectElement> & {
  label: string;
  options: Option[];
  error?: string;
};

export default function SelectField({
  id,
  label,
  options,
  error,
  className = '',
  ...props
}: Props) {
  return (
    <div>
      <label htmlFor={id} className="mb-1 block">
        {label}
      </label>

      <select id={id} className={`w-full rounded border px-3 py-2 ${className}`} {...props}>
        {options.map((option) => (
          <option key={option.value} value={option.value}>
            {option.label}
          </option>
        ))}
      </select>

      {error && <p className="mt-1 text-sm text-red-500">{error}</p>}
    </div>
  );
}
