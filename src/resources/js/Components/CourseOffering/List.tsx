import { router, usePage } from '@inertiajs/react';
import { useEffect } from 'react';
import { route } from 'ziggy-js';

import { CourseOffering } from '@/Types/courseOffering';
import { SharedProps } from '@/Types/SharedProps';

import CourseOfferingAction from './Action';

interface Props {
  courseOfferings: CourseOffering[];
}

export default function List({ courseOfferings }: Props) {
  const { flash } = usePage<SharedProps>().props;

  useEffect(() => {
    if (flash.success) {
      alert(flash.success);
    }

    if (flash.error) {
      alert(flash.error);
    }
  }, [flash]);

  const enroll = (courseOfferingId: number) => {
    router.post(
      route('enrollments.store', {
        courseOffering: courseOfferingId,
      }),
    );
  };

  const cancel = (courseOfferingId: number) => {
    router.delete(
      route('enrollments.drop', {
        courseOffering: courseOfferingId,
      }),
    );
  };

  return (
    <div>
      <h2 className="mb-2 text-lg font-bold">開講中の講義</h2>

      <table className="w-full border-collapse border">
        <thead>
          <tr>
            <th className="border px-4 py-2 text-left">講義名</th>
            <th className="border px-4 py-2">操作</th>
          </tr>
        </thead>

        <tbody>
          {courseOfferings.map((courseOffering) => (
            <tr key={courseOffering.id}>
              <td className="border px-4 py-2">{courseOffering.name}</td>

              <td className="border px-4 py-2 text-center">
                <CourseOfferingAction
                  courseOffering={courseOffering}
                  onEnroll={enroll}
                  onCancel={cancel}
                />
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}
