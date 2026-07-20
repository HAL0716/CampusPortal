import { CourseOffering } from "@/Types/courseOffering";


interface Props {
  courseOfferings: CourseOffering[];
}

export default function List({ courseOfferings }: Props) {
  return (
    <div>
      <h2 className="mb-2 text-lg font-bold">開講中の講義</h2>

      <table className="w-full border-collapse border">
        <thead>
          <tr>
            <th className="border px-4 py-2 text-left">講義名</th>
          </tr>
        </thead>

        <tbody>
          {courseOfferings.map((courseOffering) => (
            <tr key={courseOffering.id}>
              <td className="border px-4 py-2">{courseOffering.name}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}
