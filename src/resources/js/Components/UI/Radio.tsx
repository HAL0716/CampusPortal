type Props = {
  name: string;
  value: string | number;
  checked: boolean;
  label: string;
  onChange: () => void;
};

export default function Radio({ name, value, checked, label, onChange }: Props) {
  return (
    <label className="flex items-center gap-2">
      <input
        type="radio"
        name={name}
        value={value}
        checked={checked}
        onChange={onChange}
        className="h-4 w-4"
      />

      <span>{label}</span>
    </label>
  );
}
