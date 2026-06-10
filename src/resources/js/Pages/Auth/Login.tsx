import { useForm } from '@inertiajs/react';

import InputField from '@/Components/Form/InputField';
import PageTitle from '@/Components/Typography/PageTitle';
import Button from '@/Components/UI/Button';

type LoginForm = {
  login_id: string;
  password: string;
};

export default function Login() {
  const { data, setData, post, processing, errors } = useForm<LoginForm>({
    login_id: '',
    password: '',
  });

  const submit = (e: React.SubmitEvent<HTMLFormElement>) => {
    e.preventDefault();
    post('/login');
  };

  return (
    <div className="space-y-8">
      <PageTitle title="ログイン" />

      <form onSubmit={submit} className="space-y-6">
        <InputField
          label="ログインID"
          name="login_id"
          value={data.login_id}
          onChange={(e) => setData('login_id', e.target.value)}
          error={errors.login_id}
          autoComplete="username"
        />

        <InputField
          label="パスワード"
          name="password"
          type="password"
          value={data.password}
          onChange={(e) => setData('password', e.target.value)}
          error={errors.password}
          autoComplete="current-password"
        />

        <Button type="submit" loading={processing}>
          ログイン
        </Button>
      </form>
    </div>
  );
}
