import { usePage } from '@inertiajs/react';

import PageTitle from '@/Components/Typography/PageTitle';
import { SharedProps } from '@/Types/SharedProps';

export default function Index() {
  const { auth } = usePage<SharedProps>().props;

  return (
    <div className="space-y-8">
      <PageTitle title="教員用 ホーム" />
      <p>ようこそ，{auth.user?.name}さん</p>
    </div>
  );
}
