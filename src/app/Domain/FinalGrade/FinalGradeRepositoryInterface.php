<?php

namespace App\Domain\FinalGrade;

interface FinalGradeRepositoryInterface
{
    public function save(FinalGrade $finalGrade): FinalGrade;
}
