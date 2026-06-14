import ErrorText from '@/Components/UI/ErrorText';
import Radio from '@/Components/UI/Radio';

type Option = {
  value: string | number;
  label: string;
};

type Props = {
  label: string;
  name: string;
  value: string | number;
  options: Option[];
  error?: string;
  onChange: (value: string | number) => void;
};

export default function RadioField({ label, name, value, options, error, onChange }: Props) {
  return (
    <div>
      <p className="mb-2 font-medium">{label}</p>

      <div className="flex flex-wrap gap-4">
        {options.map((option) => (
          <Radio
            key={option.value}
            name={name}
            value={option.value}
            label={option.label}
            checked={value === option.value}
            onChange={() => onChange(option.value)}
          />
        ))}
      </div>

      <ErrorText message={error} />
    </div>
  );
}
