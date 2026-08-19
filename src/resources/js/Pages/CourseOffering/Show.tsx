import { Head, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';

import Button from '@/Components/Button';
import Card from '@/Components/Card';
import FlashMessage from '@/Components/FlashMessage';
import { SharedProps } from '@/Types/SharedProps';

type Status = 'none' | 'enrolled' | 'dropped' | 'completed' | 'failed' | 'teaching';

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

export default function Show() {
  const { flash, offering } = usePage<SharedProps & PageProps>().props;

  return (
    <>
      <Head title={offering.name} />

      <div className="space-y-6">
        <FlashMessage key={flash.success?.id} text={flash.success?.message} type="success" />

        <FlashMessage key={flash.error?.id} text={flash.error?.message} type="danger" />

        <Card title={offering.name} description={offering.description} />

        <Card title="担当教員" description={offering.teachers.join(', ')} />

        {/* 講義資料 */}
        <Card title="講義資料">
          {offering.materials.length > 0 ? (
            <div className="mt-4 space-y-3">
              {offering.materials.map((material) => (
                <Card key={material.id} href={`/materials/${material.id}`} title={material.title} />
              ))}
            </div>
          ) : (
            <p className="mt-4 text-sm text-gray-500">講義資料はありません。</p>
          )}
        </Card>

        {offering.status === 'none' && (
          <Button
            href={route('course-offerings.enroll', { id: offering.id })}
            label="履修登録"
            variant="info"
          />
        )}

        {offering.status === 'enrolled' && (
          <Button
            href={route('course-offerings.drop', { id: offering.id })}
            label="履修取消"
            variant="danger"
          />
        )}

        {offering.status === 'dropped' && (
          <Button
            href={route('course-offerings.enroll', { id: offering.id })}
            label="履修再登録"
            variant="info"
          />
        )}

        {offering.status === 'teaching' && (
          <Card
            href={route('course-offerings.materials.create', { id: offering.id })}
            title="講義資料追加"
            variant="info"
          />
        )}
      </div>
    </>
  );
}
