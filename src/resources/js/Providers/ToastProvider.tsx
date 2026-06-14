import { createContext, ReactNode, useCallback, useMemo, useState } from 'react';

import Toast from '@/Components/UI/Toast';

type ToastType = 'success' | 'error';

type ToastState = {
  message: string;
  type: ToastType;
};

type ToastContextType = {
  success: (message: string) => void;
  error: (message: string) => void;
};

export const ToastContext = createContext<ToastContextType | null>(null);

type Props = {
  children: ReactNode;
};

export function ToastProvider({ children }: Props) {
  const [toast, setToast] = useState<ToastState | null>(null);

  const show = useCallback((message: string, type: ToastType) => {
    setToast({
      message,
      type,
    });

    setTimeout(() => {
      setToast(null);
    }, 3000);
  }, []);

  const value = useMemo(
    () => ({
      success: (message: string) => show(message, 'success'),

      error: (message: string) => show(message, 'error'),
    }),
    [show],
  );

  return (
    <ToastContext.Provider value={value}>
      {children}

      {toast && <Toast message={toast.message} type={toast.type} />}
    </ToastContext.Provider>
  );
}
