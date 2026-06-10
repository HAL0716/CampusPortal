import ErrorText from '@/Components/UI/ErrorText';
import Input from '@/Components/UI/Input';
import Label from '@/Components/UI/Label';

type Props = {
  label: string;
  name: string;
  type?: string;
  value: string;
  onChange: React.ChangeEventHandler<HTMLInputElement>;
  error?: string;
  autoComplete?: string;
};

export default function InputField({
  label,
  name,
  type = 'text',
  value,
  onChange,
  error,
  autoComplete,
}: Props) {
  return (
    <div>
      <Label htmlFor={name}>{label}</Label>

      <Input
        id={name}
        name={name}
        type={type}
        value={value}
        onChange={onChange}
        autoComplete={autoComplete}
      />

      <ErrorText message={error} />
    </div>
  );
}
