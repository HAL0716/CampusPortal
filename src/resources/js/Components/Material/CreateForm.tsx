import { useForm } from '@inertiajs/react';
import { route } from 'ziggy-js';

import Button from '@/Components/Button';
import DateInput from '@/Components/Form/DateInput';
import FileInput from '@/Components/Form/FileInput';
import Input from '@/Components/Form/Input';
import Textarea from '@/Components/Form/Textarea';

type Props = {
  offeringId: number;
};

export default function CreateForm({ offeringId }: Props) {
  const { data, setData, post, errors, reset } = useForm({
    title: '',
    description: '',
    file: null as File | null,
    publishDate: '',
  });

  const submit = (e: React.SubmitEvent<HTMLFormElement>) => {
    e.preventDefault();

    post(route('course-offerings.materials.store', offeringId), {
      forceFormData: true,
      onSuccess: () => {
        reset();
      },
    });
  };

  return (
    <form onSubmit={submit} className="mt-4 space-y-5">
      <Input
        id="title"
        label="タイトル"
        value={data.title}
        placeholder="タイトル"
        error={errors.title}
        onChange={(value) => setData('title', value)}
      />

      <Textarea
        id="description"
        label="説明"
        value={data.description}
        placeholder="説明"
        rows={4}
        error={errors.description}
        onChange={(value) => setData('description', value)}
      />

      <FileInput
        id="file"
        label="ファイル"
        value={data.file}
        error={errors.file}
        onChange={(file) => setData('file', file)}
      />

      <DateInput
        id="publishDate"
        label="公開日"
        value={data.publishDate}
        error={errors.publishDate}
        onChange={(value) => setData('publishDate', value)}
      />

      <Button type="submit">追加</Button>
    </form>
  );
}
