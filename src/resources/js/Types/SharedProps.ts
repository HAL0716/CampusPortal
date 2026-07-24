import { PageProps } from '@inertiajs/core';

export interface SharedProps extends PageProps {
  auth: {
    user: {
      name: string;
      permissions: string[];
    } | null;
  };
  clock: {
    now: string;
  };
  flash: {
    success?: string;
    error?: string;
  };
}
