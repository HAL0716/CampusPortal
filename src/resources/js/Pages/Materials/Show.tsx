import { Head } from '@inertiajs/react';

type Material = {
  id: number;
  title: string;
  description: string;
  file_path: string;
};

type Props = {
  material: Material;
};

export default function Show({ material }: Props) {
  return (
    <>
      <Head title={material.title} />

      <h1 className="mb-4 text-xl font-bold">{material.title}</h1>

      <section className="mb-6">
        <h2 className="font-bold">説明</h2>

        <p>{material.description}</p>
      </section>

      <section className="mb-6">
        <h2 className="font-bold">ファイル</h2>

        <p>{material.file_path}</p>
      </section>
    </>
  );
}
