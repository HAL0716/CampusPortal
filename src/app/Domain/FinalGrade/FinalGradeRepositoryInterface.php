<?php

namespace App\Domain\FinalGrade;

use App\Domain\FinalGrade\Entities\FinalGrade;

interface FinalGradeRepositoryInterface
{
    public function save(FinalGrade $finalGrade): FinalGrade;
}
