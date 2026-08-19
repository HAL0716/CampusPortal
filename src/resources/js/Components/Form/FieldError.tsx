type Props = {
  id: string;
  error?: string;
};

export default function FieldError({ id, error }: Props) {
  if (!error) {
    return null;
  }

  return (
    <p id={id} className="mt-1 text-sm text-red-500">
      {error}
    </p>
  );
}
