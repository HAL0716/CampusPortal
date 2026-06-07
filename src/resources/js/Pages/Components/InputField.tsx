import { InputHTMLAttributes } from 'react';

type Props = InputHTMLAttributes<HTMLInputElement> & {
  label: string;
  error?: string;
};

export default function InputField({ id, label, error, className = '', ...props }: Props) {
  return (
    <div>
      <label htmlFor={id} className="mb-1 block">
        {label}
      </label>

      <input id={id} className={`w-full rounded border px-3 py-2 ${className}`} {...props} />

      {error && <p className="mt-1 text-sm text-red-500">{error}</p>}
    </div>
  );
}
