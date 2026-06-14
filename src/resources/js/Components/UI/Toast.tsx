type Props = {
  message: string;
  type: 'success' | 'error';
};

export default function Toast({ message, type }: Props) {
  const bgClass = type === 'success' ? 'bg-green-600' : 'bg-red-600';

  return (
    <div
      className={`fixed top-4 right-4 z-50 rounded-lg px-4 py-3 text-white shadow-lg ${bgClass} `}
    >
      {message}
    </div>
  );
}
