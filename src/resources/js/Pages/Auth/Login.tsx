import { Head, useForm } from '@inertiajs/react';
import { route } from 'ziggy-js';

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

    post(route('login.submit'));
  };

  return (
    <>
      <Head title="ログイン" />

      <h1 className="mb-4 text-xl font-bold">ログイン</h1>
      <form onSubmit={submit} className="space-y-4">
        {/* login_id */}
        <div>
          <label className="block text-sm font-medium text-gray-700">ログインID</label>
          <input
            type="text"
            autoComplete="username"
            value={data.login_id}
            onChange={(e) => setData('login_id', e.target.value)}
            className="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
          />
          {errors.login_id && <p className="mt-1 text-sm text-red-500">{errors.login_id}</p>}
        </div>

        {/* password */}
        <div>
          <label className="block text-sm font-medium text-gray-700">パスワード</label>
          <input
            type="password"
            autoComplete="current-password"
            value={data.password}
            onChange={(e) => setData('password', e.target.value)}
            className="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
          />
          {errors.password && <p className="mt-1 text-sm text-red-500">{errors.password}</p>}
        </div>

        {/* submit */}
        <button
          type="submit"
          disabled={processing}
          className="w-full rounded-lg bg-blue-600 p-2 text-white hover:bg-blue-700 disabled:opacity-50"
        >
          {processing ? 'ログイン中...' : 'ログイン'}
        </button>
      </form>
    </>
  );
}
