import { useForm } from '@inertiajs/react';
import { SyntheticEvent } from 'react';
import { route } from 'ziggy-js';

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
    <form onSubmit={submit}>
      <h1 className="mb-4 text-xl font-bold">ログインフォーム</h1>

      <label htmlFor="email" className="mb-1 block text-sm font-medium">
        メールアドレス
      </label>

      <input
        id="email"
        type="email"
        value={data.email}
        onChange={(e) => setData('email', e.target.value)}
        placeholder="Email"
        autoComplete="email"
        aria-invalid={!!errors.email}
        aria-describedby={errors.email ? 'email-error' : undefined}
        className="mb-4 w-full rounded border px-3 py-2"
      />

      {errors.email && (
        <div id="email-error" className="mb-2 text-sm text-red-500" role="alert">
          {errors.email}
        </div>
      )}

      <label htmlFor="password" className="mb-1 block text-sm font-medium">
        パスワード
      </label>

      <input
        id="password"
        type="password"
        value={data.password}
        onChange={(e) => setData('password', e.target.value)}
        placeholder="パスワード"
        autoComplete="current-password"
        aria-invalid={!!errors.password}
        aria-describedby={errors.password ? 'password-error' : undefined}
        className="mb-4 w-full rounded border px-3 py-2"
      />

      {errors.password && (
        <div id="password-error" className="mb-2 text-sm text-red-500" role="alert">
          {errors.password}
        </div>
      )}

      <button type="submit" className="w-full rounded bg-black py-2 text-white">
        Login
      </button>
    </form>
  );
}
