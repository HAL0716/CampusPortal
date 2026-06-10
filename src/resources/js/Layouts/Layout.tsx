import { Link, usePage } from '@inertiajs/react';

import { SharedProps } from '@/Types/SharedProps';

type Props = {
  children: React.ReactNode;
};

export default function Layout({ children }: Props) {
  const { appName, auth } = usePage<SharedProps>().props;

  return (
    <div className="flex min-h-screen flex-col bg-gray-100 text-gray-800">
      <header className="bg-white shadow-sm">
        <div className="mx-auto flex max-w-5xl items-center justify-between px-4 py-4">
          <Link href="/" className="text-xl font-bold text-gray-900 transition hover:text-blue-600">
            {appName}
          </Link>

          <nav className="flex items-center gap-4 text-sm font-medium">
            {auth.user ? (
              <Link href="/logout" method="post" as="button" className="hover:text-red-500">
                Logout
              </Link>
            ) : (
              <Link href="/login" className="hover:text-blue-600">
                Login
              </Link>
            )}
          </nav>
        </div>
      </header>

      <main className="mx-auto w-full max-w-5xl flex-1 px-4 py-8">
        <article className="rounded-2xl bg-white p-6 shadow">
          <div className="mx-auto w-full">{children}</div>
        </article>
      </main>

      <footer className="bg-white">
        <div className="mx-auto max-w-5xl px-4 py-4 text-center text-sm text-gray-500">
          &copy; {new Date().getFullYear()} {appName}
        </div>
      </footer>
    </div>
  );
}
