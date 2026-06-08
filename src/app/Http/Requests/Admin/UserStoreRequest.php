<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserGrade;
use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function role(): UserRole
    {
        return UserRole::from(
            $this->query('role', UserRole::STUDENT->value)
        );
    }

    public function rules(): array
    {
        $role = $this->role();

        return [
            'grade' => [
                'nullable',
                Rule::requiredIf($role === UserRole::STUDENT),
                Rule::enum(UserGrade::class),
            ],

            'department_id' => [
                'nullable',
                Rule::requiredIf(in_array($role, [
                    UserRole::STUDENT,
                    UserRole::TEACHER,
                ], true)),
                'exists:departments,id',
            ],

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
            'grade' => '学年',
            'department_id' => '学科',
            'csv' => 'CSVファイル',
        ];
    }

    public function messages(): array
    {
        return [
            'grade.required' => ':attributeは必須です。',
            'grade.enum' => ':attributeの値が不正です。',
            'department_id.required' => ':attributeは必須です。',
            'department_id.exists' => '選択された:attributeは存在しません。',
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

            if ($name === '') {
                $validator->errors()->add(
                    'csv',
                    "{$line}行目の氏名が空です。"
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
            file($this->file('csv')->getRealPath())
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
