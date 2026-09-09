import { useForm, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';

import Button from '@/Components/Button';
import Input from '@/Components/Form/Input';
import Select from '@/Components/Form/Select';
import { SharedProps } from '@/Types/SharedProps';

import FlashMessage from '../FlashMessage';

type FormData = {
  name: string;
  email: string;
  password: string;
  studentNumber: string;
  departmentId: string;
};

type PageProps = {
  departments: {
    id: number;
    name: string;
  }[];
};

export default function CreateForm() {
  const { flash, departments } = usePage<SharedProps & PageProps>().props;

  const { data, setData, post, errors, reset } = useForm<FormData>({
    name: '',
    email: '',
    password: '',
    studentNumber: '',
    departmentId: '',
  });

  const submit = (event: React.SubmitEvent<HTMLFormElement>) => {
    event.preventDefault();

    post(route('students.store'), {
      forceFormData: true,
      onSuccess: () => {
        reset();
      },
    });
  };

  const departmentOptions = departments.map((department) => ({
    value: String(department.id),
    label: department.name,
  }));

  return (
    <form onSubmit={submit} className="mt-4 space-y-5">
      <FlashMessage key={flash.success?.id} text={flash.success?.message} type="success" />

      <FlashMessage key={flash.error?.id} text={flash.error?.message} type="danger" />

      <Input
        id="name"
        label="名前"
        placeholder="名前"
        autoComplete="name"
        value={data.name}
        error={errors.name}
        onChange={(value) => setData('name', value)}
      />

      <Input
        id="studentNumber"
        label="学籍番号"
        placeholder="学籍番号"
        autoComplete="off"
        value={data.studentNumber}
        error={errors.studentNumber}
        onChange={(value) => setData('studentNumber', value)}
      />

      <Input
        id="email"
        label="メールアドレス"
        placeholder="メールアドレス"
        autoComplete="email"
        value={data.email}
        error={errors.email}
        onChange={(value) => setData('email', value)}
      />

      <Input
        id="password"
        label="パスワード"
        type="password"
        placeholder="パスワード"
        autoComplete="new-password"
        value={data.password}
        error={errors.password}
        onChange={(value) => setData('password', value)}
      />

      <Select
        id="departmentId"
        label="学科"
        placeholder="学科を選択してください"
        autoComplete="off"
        value={data.departmentId}
        error={errors.departmentId}
        options={departmentOptions}
        onChange={(value) => setData('departmentId', value)}
      />

      <Button type="submit">保存</Button>
    </form>
  );
}
