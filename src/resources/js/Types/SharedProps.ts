import { PageProps } from '@inertiajs/core';

export interface SharedProps extends PageProps {
  auth: {
    user: {
      name: string;
    } | null;
  };
}
