import { createInertiaApp } from '@inertiajs/react';

import Layout from '@/Layouts/Layout';
import { ToastProvider } from '@/Providers/ToastProvider';

createInertiaApp({
  layout: () => Layout,
  withApp(app) {
    return <ToastProvider>{app}</ToastProvider>;
  },
});
