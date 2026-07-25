import { PageProps } from '@inertiajs/core';

import { FlashProps } from './Flash';

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
  flash: FlashProps;
}
