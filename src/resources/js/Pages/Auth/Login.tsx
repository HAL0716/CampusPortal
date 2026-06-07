import { useForm } from '@inertiajs/react';
import InputField from '../Components/InputField';
import Alert from '../Components/Alert';
import PageTitle from '../Components/PageTitle';
import Button from '../Components/Button';

type Form = {
  email: string;
  password: string;
  status?: string;
};

export default function Login() {
  const { data, setData, post, processing, errors } = useForm<Form>({
    email: '',
    password: '',
  });

  return (
    <form
      onSubmit={(e) => {
        e.preventDefault();
        post('/login');
      }}
      className="space-y-4"
    >
      <PageTitle title="ログイン" />

      <Alert message={data.status} variant="error" />

      <InputField
        id="email"
        label="メールアドレス"
        type="email"
        autoComplete="email"
        value={data.email}
        error={errors.email}
        onChange={(e) => setData('email', e.target.value)}
      />

      <InputField
        id="password"
        label="パスワード"
        type="password"
        autoComplete="current-password"
        value={data.password}
        error={errors.password}
        onChange={(e) => setData('password', e.target.value)}
      />

      <Button label="ログイン" loading={processing} />
    </form>
  );
}
