import { useForm, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';

import Button from '@/Components/Button';
import { SharedProps } from '@/Types/SharedProps';

import FlashMessage from '../FlashMessage';
import DateInput from '../Form/DateInput';

type FormData = {
  endDate: string;
};

type CreateFormProps = {
  endDate: string;
};

const getEndOfMonthAfter = (date: string, months: number) => {
  const [year, month] = date.split('-').map(Number);

  const endOfMonth = new Date(year, month - 1 + months + 1, 0);

  return [
    endOfMonth.getFullYear(),
    String(endOfMonth.getMonth() + 1).padStart(2, '0'),
    String(endOfMonth.getDate()).padStart(2, '0'),
  ].join('-');
};

export default function CreateForm({ endDate }: CreateFormProps) {
  const { flash } = usePage<SharedProps>().props;

  const { data, setData, post, errors, reset } = useForm<FormData>({
    endDate: getEndOfMonthAfter(endDate, 4),
  });

  const submit = (event: React.SubmitEvent<HTMLFormElement>) => {
    event.preventDefault();

    post(route('semesters.store'), {
      forceFormData: true,
      onSuccess: () => {
        reset();
      },
    });
  };

  return (
    <form onSubmit={submit} className="mt-4 space-y-5">
      <FlashMessage key={flash.success?.id} text={flash.success?.message} type="success" />

      <FlashMessage key={flash.error?.id} text={flash.error?.message} type="danger" />

      <DateInput
        id="endDate"
        label="次学期終了日"
        value={data.endDate}
        error={errors.endDate}
        onChange={(value) => setData('endDate', value)}
      />

      <Button type="submit">追加</Button>
    </form>
  );
}
