import { createInertiaApp } from '@inertiajs/react';

import AppLayout from '@/Layouts/AppLayout';

createInertiaApp({
  layout: () => AppLayout,
});
