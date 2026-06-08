import { useForm, usePage } from '@inertiajs/react';
import PageTitle from '../../Components/PageTitle';
import SelectField from '../../Components/SelectFields';
import Button from '../../Components/Button';
import FileField from '../../Components/FileField';
import { SharedProps } from '../../../types/shared';
import Alert from '../../Components/Alert';

type Option = {
  value: number;
  label: string;
};

type Props = SharedProps & {
  grades: Option[];
  departments: Option[];
};

type Form = {
  grade: string;
  department: string;
  csv: File | null;
};

export default function Student() {
  const { flash, grades, departments } = usePage<Props>().props;

  const { data, setData, post, processing, errors, reset } = useForm<Form>({
    grade: '',
    department: '',
    csv: null,
  });

  return (
    <form
      onSubmit={(e) => {
        e.preventDefault();
        post('/admin/users', {
          onSuccess: () => reset(),
        });
      }}
      className="space-y-4"
    >
      <PageTitle title="学生管理" />

      {flash.success && <Alert message={flash.success} variant="success" />}
      {flash.error && <Alert message={flash.error} variant="error" />}

      <SelectField
        id="grade"
        label="学年"
        value={data.grade}
        onChange={(e) => setData('grade', e.target.value)}
        options={[{ value: '', label: '選択してください' }, ...grades]}
        error={errors.grade}
      />

      <SelectField
        id="department"
        label="学科"
        value={data.department}
        onChange={(e) => setData('department', e.target.value)}
        options={[{ value: '', label: '選択してください' }, ...departments]}
        error={errors.department}
      />

      <FileField
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
