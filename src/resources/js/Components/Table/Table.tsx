import { ReactNode } from 'react';

type Column<T> = {
  id: string;
  key: keyof T;
  label: string;
  render?: (row: T) => ReactNode;
};

type Props<T> = {
  data: T[];
  columns: Column<T>[];
  emptyMessage?: string;
};

export default function Table<T extends { id: number }>({
  data,
  columns,
  emptyMessage = 'データがありません。',
}: Props<T>) {
  return (
    <div className="overflow-x-auto">
      <table className="min-w-full border border-gray-200 bg-white">
        <thead>
          <tr>
            {columns.map((column) => (
              <th key={column.id} className="border-t border-b px-4 py-2 text-left">
                {column.label}
              </th>
            ))}
          </tr>
        </thead>

        <tbody>
          {data.length > 0 ? (
            data.map((row) => (
              <tr key={row.id}>
                {columns.map((column) => (
                  <td key={column.id} className="border-b px-4 py-2">
                    {column.render ? column.render(row) : String(row[column.key] ?? '')}
                  </td>
                ))}
              </tr>
            ))
          ) : (
            <tr>
              <td className="border-b px-4 py-2 text-center" colSpan={columns.length}>
                {emptyMessage}
              </td>
            </tr>
          )}
        </tbody>
      </table>
    </div>
  );
}
