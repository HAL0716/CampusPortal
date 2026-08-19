import { Head, useForm, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';

import ActionButton from '@/Components/Button';
import Card from '@/Components/Card';
import FlashMessage from '@/Components/FlashMessage';
import { SharedProps } from '@/Types/SharedProps';

type Status = 'none' | 'enrolled' | 'dropped' | 'completed' | 'failed' | 'teaching';

type Material = {
  id: number;
  title: string;
};

type CourseOffering = {
  id: number;
  name: string;
  description: string;
  status: Status;
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
      <Head title={offering.name} />

      <div className="space-y-6">
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

        <Card title={offering.name} description={offering.description} />

        <Card title="担当教員" description={offering.teachers.join(', ')} />

        {/* 講義資料 */}
        <Card title="講義資料">
          {offering.materials.length > 0 ? (
            <div className="mt-4 space-y-3">
              {offering.materials.map((material) => (
                <Card key={material.id} href={`/materials/${material.id}`} title={material.title} />
              ))}
            </div>
          ) : (
            <p className="mt-4 text-sm text-gray-500">講義資料はありません。</p>
          )}
        </Card>

        {offering.status === 'none' && (
          <ActionButton
            href={route('course-offerings.enroll', { id: offering.id })}
            label="履修登録"
            variant="info"
          />
        )}

        {offering.status === 'enrolled' && (
          <ActionButton
            href={route('course-offerings.drop', { id: offering.id })}
            label="履修取消"
            variant="danger"
          />
        )}

        {offering.status === 'dropped' && (
          <ActionButton
            href={route('course-offerings.enroll', { id: offering.id })}
            label="履修再登録"
            variant="info"
          />
        )}

        {offering.status === 'teaching' && (
          <Card title="資料の追加">
            <form onSubmit={submit} className="mt-6 space-y-5">
              <div>
                <label htmlFor="title" className="mb-2 block text-sm font-medium text-gray-700">
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
                  className="w-full rounded-md border border-gray-200 bg-gray-50 px-3 py-2 transition outline-none focus:border-gray-400 focus:bg-white focus:ring-2 focus:ring-gray-100"
                />

                {errors.title && (
                  <p id="title-error" className="mt-1 text-sm text-red-500">
                    {errors.title}
                  </p>
                )}
              </div>

              <div>
                <label
                  htmlFor="description"
                  className="mb-2 block text-sm font-medium text-gray-700"
                >
                  説明
                </label>

                <textarea
                  id="description"
                  value={data.description}
                  onChange={(e) => setData('description', e.target.value)}
                  placeholder="説明"
                  rows={4}
                  className="w-full resize-y rounded-md border border-gray-200 bg-gray-50 px-3 py-2 transition outline-none focus:border-gray-400 focus:bg-white focus:ring-2 focus:ring-gray-100"
                />
              </div>

              <div>
                <label htmlFor="file" className="mb-2 block text-sm font-medium text-gray-700">
                  ファイル
                </label>

                <label
                  htmlFor="file"
                  className="flex cursor-pointer items-center rounded-md border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-500 transition hover:border-gray-400 hover:bg-white"
                >
                  {data.file ? data.file.name : 'ファイルを選択'}
                </label>

                <input
                  id="file"
                  type="file"
                  className="hidden"
                  onChange={(e) => setData('file', e.target.files?.[0] ?? null)}
                  aria-invalid={!!errors.file}
                  aria-describedby={errors.file ? 'file-error' : undefined}
                />

                {errors.file && (
                  <p id="file-error" className="mt-1 text-sm text-red-500">
                    {errors.file}
                  </p>
                )}
              </div>

              <div>
                <label
                  htmlFor="publishDate"
                  className="mb-2 block text-sm font-medium text-gray-700"
                >
                  公開日
                </label>

                <input
                  id="publishDate"
                  type="date"
                  value={data.publishDate}
                  onChange={(e) => setData('publishDate', e.target.value)}
                  className="w-full rounded-md border border-gray-200 bg-gray-50 px-3 py-2 transition outline-none focus:border-gray-400 focus:bg-white focus:ring-2 focus:ring-gray-100"
                />
              </div>

              <button
                type="submit"
                className="w-full rounded-md bg-gray-900 py-2.5 font-medium text-white transition hover:bg-gray-700"
              >
                追加
              </button>
            </form>
          </Card>
        )}
      </div>
    </>
  );
}
