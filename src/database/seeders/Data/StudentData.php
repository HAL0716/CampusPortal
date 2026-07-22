<?php

namespace Database\Seeders\Data;

final class StudentData
{
    public static function all(): array
    {
        return [
            [
                'name' => '佐藤学生',
                'email' => 's250001@university.ac.jp',
                'password' => 'pass1234',
                'department' => '情報学科',
                'student_number' => '250001',
            ],
            [
                'name' => '鈴木学生',
                'email' => 's250002@university.ac.jp',
                'password' => 'pass1234',
                'department' => '情報学科',
                'student_number' => '250002',
            ],
            [
                'name' => '高橋学生',
                'email' => 's250003@university.ac.jp',
                'password' => 'pass1234',
                'department' => '電気学科',
                'student_number' => '250003',
            ],
            [
                'name' => '田中学生',
                'email' => 's250004@university.ac.jp',
                'password' => 'pass1234',
                'department' => '電気学科',
                'student_number' => '250004',
            ],
            [
                'name' => '伊藤学生',
                'email' => 's250005@university.ac.jp',
                'password' => 'pass1234',
                'department' => '機械学科',
                'student_number' => '250005',
            ],
            [
                'name' => '渡辺学生',
                'email' => 's250006@university.ac.jp',
                'password' => 'pass1234',
                'department' => '機械学科',
                'student_number' => '250006',
            ],
        ];
    }
}
