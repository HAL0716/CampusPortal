import { usePage } from '@inertiajs/react';

import { AdministrationCourseOffering } from '@/Types/CourseOffering';

type Props = {
  offerings: AdministrationCourseOffering[];
};

export default function Administration() {
  const { offerings } = usePage<Props>().props;

  return (
    <>
      <h1 className="mb-4 text-xl font-bold">開講講義一覧</h1>

      <table className="mb-6 w-full border-collapse border">
        <thead>
          <tr>
            <th className="border px-4 py-2 text-left">講義名</th>
          </tr>
        </thead>

        <tbody>
          {offerings.map((offering) => (
            <tr key={offering.id}>
              <td className="border px-4 py-2">{offering.name}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </>
  );
}
