import { Head, useForm } from '@inertiajs/react';
import { SyntheticEvent } from 'react';
import { route } from 'ziggy-js';

import Button from '@/Components/Button';
import Input from '@/Components/Form/Input';

export default function Login() {
  const { data, setData, post, reset, errors } = useForm({
    email: '',
    password: '',
  });

  const submit = (e: SyntheticEvent<HTMLFormElement>) => {
    e.preventDefault();

    post(route('login.store'), {
      onError: () => {
        reset('password');
      },
    });
  };

  return (
    <>
      <Head title="ログイン" />

      <h1 className="mb-4 text-xl font-bold">ログイン</h1>

      <form onSubmit={submit} className="mt-6 space-y-5">
        <Input
          id="email"
          label="メールアドレス"
          type="email"
          value={data.email}
          placeholder="Email"
          autoComplete="email"
          error={errors.email}
          onChange={(value) => setData('email', value)}
        />

        <Input
          id="password"
          label="パスワード"
          type="password"
          value={data.password}
          placeholder="パスワード"
          autoComplete="current-password"
          error={errors.password}
          onChange={(value) => setData('password', value)}
        />

        <Button type="submit">ログイン</Button>
      </form>
    </>
  );
}
