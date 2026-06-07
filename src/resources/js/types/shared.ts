import { PageProps } from '@inertiajs/core';

export interface SharedProps extends PageProps {
  app: {
    name: string;
  };
  user: {
    name: string;
    role: string;
    role_label: string;
  } | null;
}
