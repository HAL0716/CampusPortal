import { Head } from '@inertiajs/react';

import CreateForm from '@/Components/Student/CreateForm';

export default function Create() {
  return (
    <>
      <Head title="学生登録" />

      <h1 className="text-2xl font-bold">学生登録</h1>

      <CreateForm />
    </>
  );
}
