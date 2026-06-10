type Props = {
  title: string;
  className?: string;
};

export default function PageTitle({ title, className = '' }: Props) {
  return (
    <div className={`text-center ${className}`}>
      <h1 className="text-2xl font-bold text-slate-900">{title}</h1>
    </div>
  );
}
