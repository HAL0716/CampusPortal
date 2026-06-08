import { InputHTMLAttributes } from 'react';

type Props = Omit<InputHTMLAttributes<HTMLInputElement>, 'type'> & {
  label: string;
  error?: string;
};

export default function FileField({ id, label, error, className = '', ...props }: Props) {
  return (
    <div>
      <label htmlFor={id} className="mb-1 block">
        {label}
      </label>

      <input
        id={id}
        type="file"
        className={`w-full rounded border px-3 py-2 ${className}`}
        {...props}
      />

      {error && <p className="mt-1 text-sm text-red-500">{error}</p>}
    </div>
  );
}
