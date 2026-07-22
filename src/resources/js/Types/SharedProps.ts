import { PageProps } from '@inertiajs/core';

export interface SharedProps extends PageProps {
  auth: {
    user: {
      name: string;
      permissions: string[];
    } | null;
  };
  flash: {
    success?: {
      id: string;
      message: string;
    };
    error?: {
      id: string;
      message: string;
    };
  };
}
