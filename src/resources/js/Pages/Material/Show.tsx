import { Head, usePage } from '@inertiajs/react';

import Card from '@/Components/Card';

type Material = {
  id: number;
  title: string;
  description?: string;
  filePath?: string;
};

type PageProps = {
  material: Material;
};

export default function Show() {
  const { material } = usePage<PageProps>().props;

  return (
    <>
      <Head title={material.title} />

      <div className="space-y-6">
        <Card title={material.title} description={material.description} />
      </div>
    </>
  );
}
