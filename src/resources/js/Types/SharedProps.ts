import { PageProps } from '@inertiajs/core';

export interface SharedProps extends PageProps {
  auth: {
    user: {
      name: string;
      permissions: string[];
    } | null;
  };
  flash: {
    success?: string;
    error?: string;
  };
}
