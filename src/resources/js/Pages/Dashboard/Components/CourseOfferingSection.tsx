import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

type Offering = {
  id: number;
  name: string;
};

type Props = {
  offerings: Offering[];
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
                <Link
                  href={route('course-offerings.enroll', {
                    courseOffering: offering.id,
                  })}
                  method="post"
                  as="button"
                  className="bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
                >
                  履修登録
                </Link>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </section>
  );
}
