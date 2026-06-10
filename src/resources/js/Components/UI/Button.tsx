type Props = React.ButtonHTMLAttributes<HTMLButtonElement> & {
  loading?: boolean;
};

export default function Button({ loading, children, className = '', ...props }: Props) {
  return (
    <button
      {...props}
      disabled={loading || props.disabled}
      className={`w-full rounded-lg bg-blue-600 px-4 py-2 font-medium text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50 ${className} `}
    >
      {loading ? 'Loading...' : children}
    </button>
  );
}
