<?php

namespace App\Http\Controllers;

use App\Application\Enrollment\CreateEnrollmentUseCase;
use App\Application\Enrollment\DropEnrollmentUseCase;
use App\Domain\Enrollment\Exceptions\EnrollmentAlreadyExistsException;
use App\Domain\Enrollment\Exceptions\EnrollmentNotFoundException;
use App\Domain\Student\Exceptions\StudentNotFoundException;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Http\Controllers\Share\UseFlashMessage;
use App\Http\Requests\Enrollment\CreateRequest;
use App\Http\Requests\Enrollment\DropRequest;

class EnrollmentController extends Controller
{
    use UseFlashMessage;

    public function store(CreateRequest $request, CreateEnrollmentUseCase $useCase)
    {
        try {
            $useCase->execute($request->toCommand());

            return $this->backWithSuccess('履修登録しました');
        } catch (UserNotFoundException) {
            return $this->backWithError('ログインしてください。');
        } catch (StudentNotFoundException) {
            return $this->backWithError('学生情報が見つかりません。');
        } catch (EnrollmentAlreadyExistsException) {
            return $this->backWithError('すでに履修登録されています。');
        }
    }

    public function drop(DropRequest $request, DropEnrollmentUseCase $useCase)
    {
        try {
            $useCase->execute($request->toCommand());

            return $this->backWithSuccess('履修登録を取り消しました');
        } catch (UserNotFoundException) {
            return $this->backWithError('ログインしてください。');
        } catch (StudentNotFoundException) {
            return $this->backWithError('学生情報が見つかりません。');
        } catch (EnrollmentNotFoundException) {
            return $this->backWithError('履修登録が見つかりません。');
        }
    }
}
