import { useForm } from '@inertiajs/react';

import InputField from '@/Components/Form/InputField';
import RadioField from '@/Components/Form/RadioField';
import Button from '@/Components/UI/Button';

type Department = {
  id: number;
  name: string;
};

type Props = {
  departments: Department[];
};

type Form = {
  name: string;
  email: string;
  department_id: number;
};

export default function Appoint({ departments }: Props) {
  const departmentOptions = departments.map((department) => ({
    value: department.id,
    label: department.name,
  }));

  const { data, setData, post, processing, errors } = useForm<Form>({
    name: '',
    email: '',
    department_id: 0,
  });

  const submit = (e: React.SubmitEvent<HTMLFormElement>) => {
    e.preventDefault();

    if (!window.confirm('この教員を着任させますか？')) {
      return;
    }

    post('/teachers/appoint');
  };

  return (
    <form onSubmit={submit} className="space-y-6">
      <InputField
        label="名前"
        name="name"
        value={data.name}
        onChange={(e) => setData('name', e.target.value)}
        error={errors.name}
      />

      <InputField
        label="メールアドレス"
        name="email"
        type="email"
        value={data.email}
        onChange={(e) => setData('email', e.target.value)}
        error={errors.email}
      />

      <RadioField
        label="所属学科"
        name="department_id"
        value={data.department_id}
        options={departmentOptions}
        error={errors.department_id}
        onChange={(value) => setData('department_id', Number(value))}
      />

      <Button type="submit" loading={processing}>
        着任
      </Button>
    </form>
  );
}
