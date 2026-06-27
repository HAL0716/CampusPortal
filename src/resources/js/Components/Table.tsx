import React from 'react';

type Column<T> = {
  label: string;
  key: keyof T | string;
  render?: (row: T) => React.ReactNode;
};

type Props<T> = {
  data: T[];
  columns: Column<T>[];
};

export default function Table<T>({ data, columns }: Props<T>) {
  return (
    <table className="w-full border-collapse border border-gray-200">
      <thead>
        <tr className="bg-gray-50">
          {columns.map((column) => (
            <th key={column.key.toString()} className="border border-gray-200 px-4 py-2 text-left">
              {column.label}
            </th>
          ))}
        </tr>
      </thead>

      <tbody>
        {data.length === 0 ? (
          <tr>
            <td colSpan={columns.length} className="border border-gray-200 px-4 py-2 text-center">
              データなし
            </td>
          </tr>
        ) : (
          data.map((row, index) => (
            <tr key={index}>
              {columns.map((column) => (
                <td key={column.key.toString()} className="border border-gray-200 px-4 py-2">
                  {column.render ? column.render(row) : String(row[column.key as keyof T] ?? '')}
                </td>
              ))}
            </tr>
          ))
        )}
      </tbody>
    </table>
  );
}
