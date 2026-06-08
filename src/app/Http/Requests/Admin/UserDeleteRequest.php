<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UserDeleteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'csv' => [
                'required',
                'file',
                'mimes:csv,txt',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'csv' => 'CSVファイル',
        ];
    }

    public function messages(): array
    {
        return [
            'csv.required' => ':attributeは必須です。',
            'csv.file' => ':attributeはファイルでなければなりません。',
            'csv.mimes' => ':attributeはCSVファイルでなければなりません。',
        ];
    }

    public function after(): array
    {
        return [
            fn (Validator $validator) => $this->validateCsv($validator),
        ];
    }

    private function validateCsv(Validator $validator): void
    {
        $rows = $this->rawRows();

        if (count($rows) <= 1) {
            $validator->errors()->add(
                'csv',
                'CSVにデータが存在しません。'
            );

            return;
        }

        $header = array_map('trim', array_shift($rows));

        // 👇ここは流用（name,email）
        if ($header !== ['name', 'email']) {
            $validator->errors()->add(
                'csv',
                'CSVヘッダーは name,email である必要があります。'
            );

            return;
        }

        $emails = [];

        foreach ($rows as $index => $row) {
            $line = $index + 2;

            if (count($row) !== 2) {
                $validator->errors()->add(
                    'csv',
                    "{$line}行目の列数が不正です。"
                );

                return;
            }

            [$name, $email] = array_map('trim', $row);

            // nameはチェックするが削除では使わない（互換性維持）
            if ($email === '') {
                $validator->errors()->add(
                    'csv',
                    "{$line}行目のメールアドレスが空です。"
                );

                return;
            }

            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $validator->errors()->add(
                    'csv',
                    "{$line}行目のメールアドレス形式が不正です。"
                );

                return;
            }

            if (in_array($email, $emails, true)) {
                $validator->errors()->add(
                    'csv',
                    "{$line}行目のメールアドレスが重複しています。"
                );

                return;
            }

            $emails[] = $email;
        }
    }

    private function rawRows(): array
    {
        $file = $this->file('csv');

        if (! $file) {
            return [];
        }

        return array_map(
            'str_getcsv',
            file($file->getRealPath())
        );
    }

    /**
     * @return array<int, array{name: string, email: string}>
     */
    public function csvRows(): array
    {
        $rows = $this->rawRows();

        array_shift($rows);

        return array_map(
            fn (array $row) => [
                'name' => trim($row[0]),
                'email' => trim($row[1]),
            ],
            $rows
        );
    }
}
