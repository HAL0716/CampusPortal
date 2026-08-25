import { Head, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';

import Card from '@/Components/Card';
import DownloadLink from '@/Components/DownloadLink';
import FlashMessage from '@/Components/FlashMessage';
import { SharedProps } from '@/Types/SharedProps';

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
  const { flash, material } = usePage<SharedProps & PageProps>().props;

  return (
    <>
      <Head title={material.title} />

      <div className="space-y-6">
        <FlashMessage key={flash.success?.id} text={flash.success?.message} type="success" />

        <FlashMessage key={flash.error?.id} text={flash.error?.message} type="danger" />

        <Card title={material.title} description={material.description} />

        {material.filePath && (
          <DownloadLink
            href={route('materials.download', { material: material.id })}
            variant="info"
          />
        )}
      </div>
    </>
  );
}
