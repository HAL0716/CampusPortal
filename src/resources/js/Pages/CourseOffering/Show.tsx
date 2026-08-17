import { Link, usePage } from '@inertiajs/react';

type Material = {
  id: number;
  title: string;
};

type CourseOffering = {
  id: number;
  name: string;
  description: string;
  teachers: string[];
  materials: Material[];
};

type PageProps = {
  offering: CourseOffering;
};

export default function Show() {
  const { offering } = usePage<PageProps>().props;

  return (
    <>
      <h1 className="mb-4 text-xl font-bold">{offering.name}</h1>

      <p className="mb-6">{offering.description}</p>

      <section className="mb-6">
        <h2 className="mb-2 text-lg font-semibold">担当教員</h2>

        {offering.teachers.length > 0 ? (
          <ul className="list-disc pl-6">
            {offering.teachers.map((teacher) => (
              <li key={teacher}>{teacher}</li>
            ))}
          </ul>
        ) : (
          <p className="text-gray-500">担当教員がいません。</p>
        )}
      </section>

      <section>
        <h2 className="mb-2 text-lg font-semibold">講義資料</h2>

        {offering.materials.length > 0 ? (
          <ul className="space-y-1">
            {offering.materials.map((material) => (
              <li key={material.id}>
                <Link href={`/materials/${material.id}`} className="hover:underline">
                  {material.title}
                </Link>
              </li>
            ))}
          </ul>
        ) : (
          <p className="text-gray-500">講義資料はありません。</p>
        )}
      </section>
    </>
  );
}
