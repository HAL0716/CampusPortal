<?php

namespace App\Domain\FinalGrade\Repositories;

use App\Domain\FinalGrade\Entities\FinalGrade;

interface FinalGradeRepository
{
    public function save(FinalGrade $finalGrade): FinalGrade;
}
