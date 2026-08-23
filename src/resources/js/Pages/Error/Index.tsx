import { Head } from '@inertiajs/react';

type Props = {
  message: string;
};

export default function Index({ message }: Props) {
  return (
    <>
      <Head title="エラー" />

      <div className="flex min-h-screen items-center justify-center">
        <div className="text-center">
          <h1 className="text-2xl font-bold">エラーが発生しました</h1>

          <p className="mt-4 text-gray-600">{message}</p>
        </div>
      </div>
    </>
  );
}
