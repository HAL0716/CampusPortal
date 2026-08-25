import { Head, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';

import Button from '@/Components/Button';
import Card from '@/Components/Card';
import FlashMessage from '@/Components/FlashMessage';
import { SharedProps } from '@/Types/SharedProps';

type Status =
  | 'none'
  | 'enrolled'
  | 'dropped'
  | 'completed'
  | 'failed'
  | 'teaching'
  | 'not_teaching';

type Material = {
  id: number;
  title: string;
};

type CourseOffering = {
  id: number;
  name: string;
  description: string;
  status: Status;
  teachers: string[];
  materials: Material[];
};

type PageProps = {
  offering: CourseOffering;
};

type Action = {
  type: 'button' | 'card';
  title: string;
  href: string;
  variant: 'default' | 'info' | 'danger' | 'success' | 'warning' | 'accent';
};

export default function Show() {
  const { flash, offering } = usePage<SharedProps & PageProps>().props;

  const actionsByStatus: Record<Status, Action[]> = {
    none: [
      {
        type: 'button',
        title: '履修登録',
        href: route('course-offerings.enroll', { id: offering.id }),
        variant: 'info',
      },
    ],

    enrolled: [
      {
        type: 'button',
        title: '履修取消',
        href: route('course-offerings.drop', { id: offering.id }),
        variant: 'danger',
      },
    ],

    dropped: [
      {
        type: 'button',
        title: '履修再登録',
        href: route('course-offerings.enroll', { id: offering.id }),
        variant: 'info',
      },
    ],

    completed: [],

    failed: [],

    teaching: [
      {
        type: 'card',
        title: '講義資料追加',
        href: route('course-offerings.materials.create', { id: offering.id }),
        variant: 'info',
      },
      {
        type: 'card',
        title: '最終成績',
        href: route('course-offerings.final-grades.index', { id: offering.id }),
        variant: 'info',
      },
    ],

    not_teaching: [],
  };

  const actions = actionsByStatus[offering.status];

  return (
    <>
      <Head title={offering.name} />

      <div className="space-y-6">
        <FlashMessage key={flash.success?.id} text={flash.success?.message} type="success" />

        <FlashMessage key={flash.error?.id} text={flash.error?.message} type="danger" />

        <Card title={offering.name} description={offering.description} />

        <Card title="担当教員" description={offering.teachers.join(', ')} />

        <Card title="講義資料">
          {offering.materials.length > 0 ? (
            <div className="mt-4 space-y-3">
              {offering.materials.map((material) => (
                <Card
                  key={material.id}
                  href={route('materials.show', { material: material.id })}
                  title={material.title}
                />
              ))}
            </div>
          ) : (
            <p className="mt-4 text-sm text-gray-500">講義資料はありません。</p>
          )}
        </Card>

        {actions.map((action) =>
          action.type === 'button' ? (
            <Button
              key={action.title}
              href={action.href}
              label={action.title}
              variant={action.variant}
            ></Button>
          ) : (
            <Card
              key={action.title}
              href={action.href}
              title={action.title}
              variant={action.variant}
            />
          ),
        )}
      </div>
    </>
  );
}
