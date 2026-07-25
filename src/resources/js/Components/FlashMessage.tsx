import { useEffect, useState } from 'react';

type FlashMessageProps = {
  key: string;
  type: 'success' | 'error';
  children: React.ReactNode;
  duration?: number;
};

export default function FlashMessage({ type, children, duration = 3000 }: FlashMessageProps) {
  const [visible, setVisible] = useState(true);

  useEffect(() => {
    const timer = setTimeout(() => {
      setVisible(false);
    }, duration);

    return () => clearTimeout(timer);
  }, [duration]);

  if (!visible) {
    return null;
  }

  const styles = {
    success: 'bg-green-100 text-green-800',
    error: 'bg-red-100 text-red-800',
  };

  return <div className={`mb-4 rounded p-4 ${styles[type]}`}>{children}</div>;
}
