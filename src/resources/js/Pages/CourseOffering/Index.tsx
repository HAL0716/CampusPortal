import { Head, Link, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';

type PageProps = {
  offerings: {
    id: number;
    name: string;
    description: string;
  }[];
};

const MAX_DESCRIPTION_LENGTH = 50;

const truncate = (text: string, maxLength: number) =>
  text.length > maxLength ? `${text.slice(0, maxLength)}…` : text;

export default function Index() {
  const { offerings } = usePage<PageProps>().props;

  return (
    <>
      <Head title="開講科目" />

      <h1 className="mb-6 text-xl font-bold">開講科目</h1>

      {offerings.length > 0 ? (
        <div className="space-y-3">
          {offerings.map((offering) => (
            <Link
              key={offering.id}
              href={route('course-offerings.show', offering.id)}
              className="block rounded-lg border border-gray-200 bg-white p-5 shadow-sm transition hover:border-gray-300 hover:shadow-md"
            >
              <h2 className="font-semibold text-gray-900">{offering.name}</h2>

              <p className="mt-2 text-sm text-gray-500">
                {truncate(offering.description ?? '', MAX_DESCRIPTION_LENGTH)}
              </p>
            </Link>
          ))}
        </div>
      ) : (
        <p className="text-gray-500">開講科目がありません。</p>
      )}
    </>
  );
}
