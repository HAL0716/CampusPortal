<?php

namespace App\Application\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;

final class ForbiddenException extends AuthorizationException {}
