import { Link } from '@inertiajs/react';

type Props = {
  title: string;
  description: string;
  href: string;
};

export default function MenuCard({ title, description, href }: Props) {
  return (
    <Link
      href={href}
      className="block rounded-lg border border-gray-200 bg-white p-6 shadow-sm transition hover:shadow-md"
    >
      <h2 className="text-lg font-semibold">{title}</h2>
      <p className="mt-2 text-sm text-gray-600">{description}</p>
    </Link>
  );
}
