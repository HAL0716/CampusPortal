<?php

namespace App\Infrastructure\Repositories;

use App\Application\Contexts\User\Duplicate\UserDuplicateDetector;
use App\Application\Contexts\User\Duplicate\UserDuplicateTarget;
use App\Application\Services\Security\PasswordHasher;
use App\Domain\User\Entities\User;
use App\Domain\User\Exceptions\UserAlreadyExistsException;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Repositories\UserRepository;
use App\Domain\User\ValueObjects\UserEmail;
use App\Domain\User\ValueObjects\UserId;
use App\Domain\User\ValueObjects\UserPassword;
use App\Models\User as UserModel;
use Illuminate\Database\QueryException;

final class EloquentUserRepository implements UserRepository
{
    public function __construct(
        private readonly PasswordHasher $hasher,
        private readonly UserDuplicateDetector $duplicateDetector
    ) {}

    public function save(User $user): User
    {
        $model = new UserModel;

        if ($user->id() !== null) {
            $model = UserModel::find($user->requireId()->value());

            if ($model === null) {
                throw new UserNotFoundException;
            }
        }

        $model->email = $user->email()->value();
        $model->password = $user->password()->isHashed()
            ? $user->password()->value()
            : $this->hasher->hash($user->password()->value());
        $model->name = $user->name();

        try {
            $model->save();
        } catch (QueryException $e) {
            if ($this->duplicateDetector->isDuplicate($e, UserDuplicateTarget::EMAIL)) {
                throw new UserAlreadyExistsException;
            }

            throw $e;
        }

        return $this->toEntity($model);
    }

    public function findById(UserId $id): ?User
    {
        $model = UserModel::find($id->value());

        return $model ? $this->toEntity($model) : null;
    }

    public function findByEmail(UserEmail $email): ?User
    {
        $model = UserModel::where('email', $email->value())->first();

        return $model ? $this->toEntity($model) : null;
    }

    private function toEntity(UserModel $model): User
    {
        return User::reconstruct(
            new UserId((int) $model->id),
            new UserEmail($model->email),
            UserPassword::fromHash($model->password),
            $model->name
        );
    }
}
