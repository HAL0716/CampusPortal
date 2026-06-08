import { useForm, usePage } from '@inertiajs/react';
import { useState } from 'react';

import PageTitle from '../../Components/PageTitle';
import FileField from '../../Components/FileField';
import Button from '../../Components/Button';
import Alert from '../../Components/Alert';

type Props = {
  flash: {
    success?: string;
    error?: string;
  };
};

type Form = {
  csv: File | null;
};

export default function Delete() {
  const { flash } = usePage<Props>().props;

  const { setData, post, processing, errors, reset } = useForm<Form>({
    csv: null,
  });

  const [fileKey, setFileKey] = useState(0);

  return (
    <form
      onSubmit={(e) => {
        e.preventDefault();

        post(`/admin/users/destroy`, {
          onSuccess: () => {
            reset();
            setFileKey((p) => p + 1);
          },
        });
      }}
      className="space-y-4"
    >
      <PageTitle title="アカウント削除" />

      {flash.success && <Alert message={flash.success} variant="success" />}

      {flash.error && <Alert message={flash.error} variant="error" />}

      <FileField
        key={fileKey}
        id="csv"
        label="CSVファイル"
        accept=".csv,text/csv"
        onChange={(e) => setData('csv', e.target.files?.[0] ?? null)}
        error={errors.csv}
      />

      <Button label="削除" loading={processing} />
    </form>
  );
}
