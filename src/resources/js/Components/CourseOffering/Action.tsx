import { CourseOffering } from '@/Types/courseOffering';

interface Props {
  courseOffering: CourseOffering;
  onEnroll: (courseOfferingId: number) => void;
  onCancel: (courseOfferingId: number) => void;
}

interface ActionButtonProps {
  label: string;
  color: 'blue' | 'red';
  onClick: () => void;
}

function ActionButton({ label, color, onClick }: ActionButtonProps) {
  const colorClass = {
    blue: 'bg-blue-600 hover:bg-blue-700',
    red: 'bg-red-600 hover:bg-red-700',
  }[color];

  return (
    <button
      type="button"
      className={`rounded px-3 py-1 text-white ${colorClass}`}
      onClick={onClick}
    >
      {label}
    </button>
  );
}

export default function CourseOfferingAction({ courseOffering, onEnroll, onCancel }: Props) {
  switch (courseOffering.status) {
    case 'enrolled':
      return (
        <ActionButton label="履修取消" color="red" onClick={() => onCancel(courseOffering.id)} />
      );

    case 'dropped':
      return <span className="font-medium text-gray-500">取消済</span>;

    case 'completed':
      return <span className="font-medium text-gray-500">履修済</span>;

    default:
      return (
        <ActionButton label="履修登録" color="blue" onClick={() => onEnroll(courseOffering.id)} />
      );
  }
}
