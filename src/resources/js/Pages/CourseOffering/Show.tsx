import { Link, useForm, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';

import FlashMessage from '@/Components/FlashMessage';
import { SharedProps } from '@/Types/SharedProps';

type Material = {
  id: number;
  title: string;
};

type CourseOffering = {
  id: number;
  name: string;
  description: string;
  teachers: string[];
  materials: Material[];
};

type PageProps = {
  offering: CourseOffering;
};

export default function Show() {
  const { flash, offering } = usePage<SharedProps & PageProps>().props;

  const { data, setData, post, errors, reset } = useForm({
    title: '',
    description: '',
    file: null as File | null,
    publishDate: '',
  });

  const submit = (e: React.SubmitEvent<HTMLFormElement>) => {
    e.preventDefault();

    post(route('course-offerings.materials.store', { id: offering.id }), {
      forceFormData: true,
      onSuccess: () => reset(),
    });
  };

  return (
    <>
      <h1 className="mb-4 text-xl font-bold">{offering.name}</h1>

      <p className="mb-6">{offering.description}</p>

      <section className="mb-6">
        <h2 className="mb-2 text-lg font-semibold">担当教員</h2>

        {offering.teachers.length > 0 ? (
          <ul className="list-disc pl-6">
            {offering.teachers.map((teacher) => (
              <li key={teacher}>{teacher}</li>
            ))}
          </ul>
        ) : (
          <p className="text-gray-500">担当教員がいません。</p>
        )}
      </section>

      <section className="mb-6">
        <h2 className="mb-2 text-lg font-semibold">講義資料</h2>

        {offering.materials.length > 0 ? (
          <ul className="space-y-1">
            {offering.materials.map((material) => (
              <li key={material.id}>
                <Link href={`/materials/${material.id}`} className="hover:underline">
                  {material.title}
                </Link>
              </li>
            ))}
          </ul>
        ) : (
          <p className="text-gray-500">講義資料はありません。</p>
        )}
      </section>

      <section>
        <h2 className="mb-2 text-lg font-semibold">資料の追加</h2>

        {flash.success && (
          <FlashMessage key={flash.success.id} type="success">
            {flash.success.message}
          </FlashMessage>
        )}

        {flash.error && (
          <FlashMessage key={flash.error.id} type="error">
            {flash.error.message}
          </FlashMessage>
        )}

        <form onSubmit={submit} className="mt-6 space-y-5">
          <label htmlFor="title" className="mb-1 block text-sm font-medium">
            タイトル
          </label>

          <input
            id="title"
            type="text"
            value={data.title}
            onChange={(e) => setData('title', e.target.value)}
            placeholder="タイトル"
            aria-invalid={!!errors.title}
            aria-describedby={errors.title ? 'title-error' : undefined}
            className="mb-4 w-full rounded border px-3 py-2"
          />
          {errors.title && (
            <p id="title-error" className="mb-2 text-sm text-red-500">
              {errors.title}
            </p>
          )}

          <label htmlFor="description" className="mb-1 block text-sm font-medium">
            説明
          </label>

          <textarea
            id="description"
            value={data.description}
            onChange={(e) => setData('description', e.target.value)}
            placeholder="説明"
            className="mb-4 w-full rounded border px-3 py-2"
          />

          <label htmlFor="file" className="mb-1 block text-sm font-medium">
            ファイル
          </label>

          <label
            htmlFor="file"
            className="mb-4 flex cursor-pointer items-center rounded border px-3 py-2 text-gray-500"
          >
            {data.file ? data.file.name : 'ファイルを選択'}
          </label>

          <input
            id="file"
            type="file"
            className="mb-4 hidden"
            onChange={(e) => setData('file', e.target.files?.[0] ?? null)}
            aria-invalid={!!errors.file}
            aria-describedby={errors.file ? 'file-error' : undefined}
          />
          {errors.file && (
            <p id="file-error" className="mb-1 text-sm text-red-500">
              {errors.file}
            </p>
          )}

          <label htmlFor="publishDate" className="mb-1 block text-sm font-medium">
            公開日
          </label>

          <input
            id="publishDate"
            type="date"
            value={data.publishDate}
            onChange={(e) => setData('publishDate', e.target.value)}
            className="mb-4 w-full rounded border px-3 py-2"
          />

          <button type="submit" className="w-full rounded bg-black py-2 text-white">
            追加
          </button>
        </form>
      </section>
    </>
  );
}
