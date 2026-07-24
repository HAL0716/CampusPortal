import { usePage } from '@inertiajs/react';
import { PropsWithChildren } from 'react';

import { SharedProps } from '@/Types/SharedProps';

export default function AppLayout({ children }: PropsWithChildren) {
  const { clock } = usePage<SharedProps>().props;

  return (
    <div className="relative flex min-h-screen items-center justify-center bg-gray-100">
      <div className="absolute top-4 right-4 text-sm text-gray-500">{clock.now}</div>

      <main className="w-full max-w-md rounded bg-white p-8 shadow">{children}</main>
    </div>
  );
}
