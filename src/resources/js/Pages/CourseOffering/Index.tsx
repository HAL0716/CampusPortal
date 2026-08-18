import { Head, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';

import Card from '@/Components/Card';

type PageProps = {
  offerings: {
    id: number;
    name: string;
    description?: string | null;
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
            <Card
              key={offering.id}
              href={route('course-offerings.show', offering.id)}
              title={offering.name}
              description={truncate(offering.description ?? '', MAX_DESCRIPTION_LENGTH)}
            />
          ))}
        </div>
      ) : (
        <p className="text-gray-500">開講科目がありません。</p>
      )}
    </>
  );
}
