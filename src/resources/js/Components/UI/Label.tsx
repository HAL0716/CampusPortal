type Props = React.LabelHTMLAttributes<HTMLLabelElement>;

export default function Label({ className = '', ...props }: Props) {
  return (
    <label {...props} className={`mb-2 block text-sm font-medium text-slate-700 ${className}`} />
  );
}
