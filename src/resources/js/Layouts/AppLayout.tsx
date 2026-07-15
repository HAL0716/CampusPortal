import { PropsWithChildren } from 'react';

export default function AppLayout({ children }: PropsWithChildren) {
  return (
    <div className="flex min-h-screen items-center justify-center bg-gray-100">
      <main className="w-full max-w-md rounded bg-white p-8 shadow">{children}</main>
    </div>
  );
}
