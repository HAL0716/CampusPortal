<?php

namespace Database\Seeders\Data;

use App\Domain\Position\PositionType;

final class TeacherData
{
    public static function all(): array
    {
        return [
            [
                'name' => '佐藤教授',
                'email' => 'sato@university.ac.jp',
                'password' => 'pass1234',
                'position' => PositionType::PROFESSOR,
            ],
            [
                'name' => '鈴木准教授',
                'email' => 'suzuki@university.ac.jp',
                'password' => 'pass1234',
                'position' => PositionType::ASSOCIATE,
            ],
            [
                'name' => '高橋助教',
                'email' => 'takahashi@university.ac.jp',
                'password' => 'pass1234',
                'position' => PositionType::ASSISTANT,
            ],
            [
                'name' => '田中教授',
                'email' => 'tanaka@university.ac.jp',
                'password' => 'pass1234',
                'position' => PositionType::PROFESSOR,
            ],
            [
                'name' => '伊藤准教授',
                'email' => 'ito@university.ac.jp',
                'password' => 'pass1234',
                'position' => PositionType::ASSOCIATE,
            ],
            [
                'name' => '渡辺助教',
                'email' => 'watanabe@university.ac.jp',
                'password' => 'pass1234',
                'position' => PositionType::ASSISTANT,
            ],
            [
                'name' => '山本教授',
                'email' => 'yamamoto@university.ac.jp',
                'password' => 'pass1234',
                'position' => PositionType::PROFESSOR,
            ],
            [
                'name' => '中村准教授',
                'email' => 'nakamura@university.ac.jp',
                'password' => 'pass1234',
                'position' => PositionType::ASSOCIATE,
            ],
            [
                'name' => '小林助教',
                'email' => 'kobayashi@university.ac.jp',
                'password' => 'pass1234',
                'position' => PositionType::ASSISTANT,
            ],
        ];
    }
}
