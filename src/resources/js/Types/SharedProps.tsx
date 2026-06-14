import { PageProps } from '@inertiajs/core';

export interface SharedProps extends PageProps {
  appName: string;
  auth: {
    user: {
      name: string;
    } | null;
  };
  flash: {
    success?: string;
    error?: string;
  };
}
