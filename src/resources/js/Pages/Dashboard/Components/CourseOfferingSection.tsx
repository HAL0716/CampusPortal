import { CourseOffering } from '@/Types/CourseOffering';

import CourseOfferingAction from './CourseOfferingAction';

type Props = {
  offerings: CourseOffering[];
};

export default function CourseOfferingSection({ offerings }: Props) {
  return (
    <section>
      <h2 className="mb-2 text-lg font-semibold">開講科目一覧</h2>

      <table className="mb-6 w-full border-collapse border">
        <thead>
          <tr>
            <th className="border px-4 py-2 text-left">講義名</th>
            <th className="border px-4 py-2">操作</th>
          </tr>
        </thead>

        <tbody>
          {offerings.map((offering) => (
            <tr key={offering.id}>
              <td className="border px-4 py-2">{offering.name}</td>
              <td className="border px-4 py-2 text-center">
                <CourseOfferingAction offering={offering} />
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </section>
  );
}
