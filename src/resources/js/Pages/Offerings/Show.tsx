import { Head, Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

type Props = {
  offering: {
    id: number;
    course: {
      name: string;
      description: string;
    };
    materials: {
      id: number;
      title: string;
    }[];
    assignments: {
      id: number;
      title: string;
    }[];
  };
};

export default function Show({ offering }: Props) {
  return (
    <>
      <Head title={offering.course.name} />

      <h1 className="mb-4 text-xl font-bold">{offering.course.name}</h1>

      <section className="mb-6">
        <h2 className="mb-2 font-bold">講義情報</h2>

        <p>{offering.course.description}</p>
      </section>

      <section className="mb-6">
        <h2 className="mb-2 font-bold">講義資料</h2>

        <ul>
          {offering.materials.map((material) => (
            <li key={material.id}>
              <Link
                href={route('offerings.materials.show', {
                  offering: offering.id,
                  material: material.id,
                })}
                className="text-blue-500 underline hover:text-blue-700"
              >
                {material.title}
              </Link>
            </li>
          ))}
        </ul>
      </section>

      <section className="mb-6">
        <h2 className="mb-2 font-bold">課題</h2>

        <ul>
          {offering.assignments.map((assignment) => (
            <li key={assignment.id}>{assignment.title}</li>
          ))}
        </ul>
      </section>
    </>
  );
}
