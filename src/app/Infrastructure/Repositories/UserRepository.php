<?php

namespace App\Infrastructure\Repositories;

use App\Application\Security\PasswordHasherInterface;
use App\Application\User\UserDuplicateDetectorInterface;
use App\Application\User\UserDuplicateTarget;
use App\Domain\User\Exceptions\UserAlreadyExistsException;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\User;
use App\Domain\User\UserEmail;
use App\Domain\User\UserId;
use App\Domain\User\UserPassword;
use App\Domain\User\UserRepositoryInterface;
use App\Models\User as UserModel;
use Illuminate\Database\QueryException;

final class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly PasswordHasherInterface $hasher,
        private readonly UserDuplicateDetectorInterface $duplicateDetector
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
