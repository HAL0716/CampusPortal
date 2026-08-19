import FieldError from '@/Components/Form/FieldError';
import Label from '@/Components/Form/Label';

type Props = {
  id: string;
  label: string;
  value: File | null;
  error?: string;
  onChange: (file: File | null) => void;
};

export default function FileInput({ id, label, value, error, onChange }: Props) {
  const errorId = `${id}-error`;

  return (
    <div>
      <Label htmlFor={id}>{label}</Label>

      <label
        htmlFor={id}
        className="flex cursor-pointer items-center rounded-md border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-500 transition hover:border-gray-400 hover:bg-white"
      >
        {value ? value.name : 'ファイルを選択'}
      </label>

      <input
        id={id}
        type="file"
        className="hidden"
        onChange={(e) => onChange(e.target.files?.[0] ?? null)}
        aria-invalid={!!error}
        aria-describedby={error ? errorId : undefined}
      />

      <FieldError id={errorId} error={error} />
    </div>
  );
}
