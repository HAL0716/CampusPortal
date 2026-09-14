<?php

namespace App\Application\Services\Database;

enum RowLockMode
{
    case NONE;
    case FOR_UPDATE;
}
