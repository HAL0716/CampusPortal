import FieldError from '@/Components/Form/FieldError';
import Label from '@/Components/Form/Label';

type Props = {
  id: string;
  label: string;
  value: string;
  placeholder?: string;
  rows?: number;
  error?: string;
  onChange: (value: string) => void;
};

export default function Textarea({
  id,
  label,
  value,
  placeholder,
  rows = 4,
  error,
  onChange,
}: Props) {
  const errorId = `${id}-error`;

  return (
    <div>
      <Label htmlFor={id}>{label}</Label>

      <textarea
        id={id}
        value={value}
        onChange={(e) => onChange(e.target.value)}
        placeholder={placeholder}
        rows={rows}
        aria-invalid={!!error}
        aria-describedby={error ? errorId : undefined}
        className="w-full resize-y rounded-md border border-gray-200 bg-gray-50 px-3 py-2 transition outline-none focus:border-gray-400 focus:bg-white focus:ring-2 focus:ring-gray-100"
      />

      <FieldError id={errorId} error={error} />
    </div>
  );
}
