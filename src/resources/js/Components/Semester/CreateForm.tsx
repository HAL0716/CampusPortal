import { useForm, usePage } from '@inertiajs/react';
import { useState } from 'react';
import { route } from 'ziggy-js';

import Button from '@/Components/Button';
import { SharedProps } from '@/Types/SharedProps';

import FlashMessage from '../FlashMessage';
import Input from '../Form/Input';

type FormData = {
  endDate: string;
};

type CreateFormProps = {
  endDate: string;
};

const getEndOfMonthAfter = (date: string, months: number) => {
  const [year, month] = date.split('-').map(Number);
  const endOfMonth = new Date(year, month + months, 0);

  return endOfMonth.toISOString().slice(0, 10);
};

const getDateParts = (date: string) => {
  const [year = '', month = '', day = ''] = date.split('-');

  return { year, month, day };
};

export default function CreateForm({ endDate }: CreateFormProps) {
  const { flash } = usePage<SharedProps>().props;

  const { setData, post, errors, reset } = useForm<FormData>({
    endDate: '',
  });

  const placeholder = getDateParts(getEndOfMonthAfter(endDate, 4));

  const [date, setDate] = useState({
    year: '',
    month: '',
    day: '',
  });

  const updateDate = (key: keyof typeof date, value: string) => {
    const nextDate = {
      ...date,
      [key]: value.replace(/\D/g, ''),
    };

    setDate(nextDate);

    const { year, month, day } = nextDate;

    setData(
      'endDate',
      year && month && day ? `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}` : '',
    );
  };

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

      <div className="flex gap-2">
        {(['year', 'month', 'day'] as const).map((key) => (
          <Input
            key={key}
            id={`endDate-${key}`}
            label={{ year: '年', month: '月', day: '日' }[key]}
            type="text"
            inputMode="numeric"
            value={date[key]}
            placeholder={placeholder[key]}
            onChange={(value) => updateDate(key, value)}
          />
        ))}
      </div>

      {errors.endDate && <p className="text-sm text-red-600">{errors.endDate}</p>}

      <Button type="submit">追加</Button>
    </form>
  );
}
