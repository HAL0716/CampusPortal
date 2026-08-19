import type { ReactNode } from 'react';

type Props = {
  htmlFor: string;
  children: ReactNode;
};

export default function Label({ htmlFor, children }: Props) {
  return (
    <label htmlFor={htmlFor} className="mb-2 block text-sm font-medium text-gray-700">
      {children}
    </label>
  );
}
