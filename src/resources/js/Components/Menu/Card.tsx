import { Link } from '@inertiajs/react';

type Props = {
  title: string;
  description: string;
  href: string;
};

export default function Card({ title, description, href }: Props) {
  return (
    <Link
      href={href}
      className="block rounded-xl border border-slate-200 p-6 transition hover:border-blue-500 hover:shadow-md"
    >
      <h2 className="text-lg font-semibold text-slate-900">{title}</h2>

      <p className="mt-2 text-sm text-slate-600">{description}</p>
    </Link>
  );
}
