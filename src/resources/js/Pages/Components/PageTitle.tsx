type Props = {
  title: string;
};

export default function PageTitle({ title }: Props) {
  return <h1 className="text-2xl font-bold">{title}</h1>;
}
