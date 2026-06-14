type Props = {
  title: string;
  className?: string;
};

export default function SectionTitle({ title, className = '' }: Props) {
  return <h2 className={`text-lg font-semibold text-slate-800 ${className}`}>{title}</h2>;
}
