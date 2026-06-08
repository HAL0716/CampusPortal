type Props = {
  message?: React.ReactNode;
  variant?: 'success' | 'error' | 'warning' | 'info';
};

export default function Alert({ message, variant = 'info' }: Props) {
  if (!message) {
    return null;
  }

  const styles = {
    success: 'border-green-300 bg-green-50 text-green-700',
    error: 'border-red-300 bg-red-50 text-red-700',
    warning: 'border-yellow-300 bg-yellow-50 text-yellow-700',
    info: 'border-blue-300 bg-blue-50 text-blue-700',
  };

  return (
    <div className={`rounded border px-4 py-3 text-sm ${styles[variant]}`} role="alert">
      {message}
    </div>
  );
}
