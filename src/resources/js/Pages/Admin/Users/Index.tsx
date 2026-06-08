import { useForm, usePage } from '@inertiajs/react';
import { useState } from 'react';

import PageTitle from '../../Components/PageTitle';
import SelectField from '../../Components/SelectFields';
import FileField from '../../Components/FileField';
import Button from '../../Components/Button';
import Alert from '../../Components/Alert';

type Role = 'student' | 'teacher';

type Option = {
  value: number;
  label: string;
};

type Props = {
  role: Role;
  grades: Option[];
  departments: Option[];
  flash: {
    success?: string;
    error?: string;
    download_url?: string;
  };
};

type Form = {
  grade?: string;
  department_id: string;
  csv: File | null;
};

export default function Index() {
  const { role, grades, departments, flash } = usePage<Props>().props;

  const isStudent = role === 'student';

  const { data, setData, post, processing, errors, reset } = useForm<Form>({
    grade: '',
    department_id: '',
    csv: null,
  });

  const [fileKey, setFileKey] = useState(0);
  const [downloadUsed, setDownloadUsed] = useState(false);

  return (
    <form
      onSubmit={(e) => {
        e.preventDefault();

        post(`/admin/users?role=${role}`, {
          onSuccess: () => {
            reset();
            setFileKey((p) => p + 1);
          },
        });
      }}
      className="space-y-4"
    >
      <PageTitle title={isStudent ? '学生管理' : '教員管理'} />

      {flash.success && <Alert message={flash.success} variant="success" />}

      {flash.download_url && (
        <Alert
          message={
            <a
              href={flash.download_url}
              onClick={() => setDownloadUsed(true)}
              className={`text-blue-600 underline ${
                downloadUsed ? 'pointer-events-none opacity-50' : ''
              }`}
            >
              初期パスワードCSVをダウンロード
            </a>
          }
          variant="success"
        />
      )}

      {flash.error && <Alert message={flash.error} variant="error" />}

      {isStudent && (
        <SelectField
          id="grade"
          label="学年"
          value={data.grade ?? ''}
          onChange={(e) => setData('grade', e.target.value)}
          options={[{ value: '', label: '選択してください' }, ...grades]}
          error={errors.grade}
        />
      )}

      <SelectField
        id="department_id"
        label="学科"
        value={data.department_id}
        onChange={(e) => setData('department_id', e.target.value)}
        options={[{ value: '', label: '選択してください' }, ...departments]}
        error={errors.department_id}
      />

      <FileField
        key={fileKey}
        id="csv"
        label="CSVファイル"
        accept=".csv,text/csv"
        onChange={(e) => setData('csv', e.target.files?.[0] ?? null)}
        error={errors.csv}
      />

      <Button label="送信" loading={processing} />
    </form>
  );
}
