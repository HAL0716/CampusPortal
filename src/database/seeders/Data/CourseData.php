<?php

namespace Database\Seeders\Data;

use App\Domain\Academic\Enums\Term;

final class CourseData
{
    public static function all(): array
    {
        return [
            [
                'name' => '機械工学基礎',
                'description' => '機械工学の基礎となる力学や材料について学ぶ。',
                'term' => Term::FIRST,
                'departments' => ['機械学科'],
            ],
            [
                'name' => 'CAD設計',
                'description' => 'CADを利用した機械設計技術について学ぶ。',
                'term' => Term::SECOND,
                'departments' => ['機械学科'],
            ],

            [
                'name' => '電気回路',
                'description' => '電気回路の基礎理論と解析方法について学ぶ。',
                'term' => Term::FIRST,
                'departments' => ['電気学科'],
            ],
            [
                'name' => '電子工学',
                'description' => '半導体や電子デバイスの基礎について学ぶ。',
                'term' => Term::SECOND,
                'departments' => ['電気学科'],
            ],

            [
                'name' => 'Webプログラミング',
                'description' => 'Webアプリケーション開発の基礎について学ぶ。',
                'term' => Term::FIRST,
                'departments' => ['情報学科'],
            ],
            [
                'name' => 'データベース',
                'description' => 'データベース設計とSQLについて学ぶ。',
                'term' => Term::SECOND,
                'departments' => ['情報学科'],
            ],

            // 共通科目
            [
                'name' => '情報リテラシー',
                'description' => 'コンピュータ活用や情報セキュリティの基礎を学ぶ。',
                'term' => Term::FIRST,
                'departments' => ['機械学科', '電気学科', '情報学科'],
            ],
            [
                'name' => 'キャリアデザイン',
                'description' => '将来の進路や職業選択について考える。',
                'term' => Term::THIRD,
                'departments' => ['機械学科', '電気学科', '情報学科'],
            ],
        ];
    }
}
