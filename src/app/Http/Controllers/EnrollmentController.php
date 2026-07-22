<?php

namespace App\Http\Controllers;

use App\Application\Enrollment\CreateEnrollmentUseCase;
use App\Application\Enrollment\DropEnrollmentUseCase;
use App\Domain\Enrollment\Exceptions\EnrollmentAlreadyExistsException;
use App\Domain\Enrollment\Exceptions\EnrollmentNotFoundException;
use App\Domain\Student\Exceptions\StudentNotFoundException;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Http\Requests\Enrollment\CreateRequest;
use App\Http\Requests\Enrollment\DropRequest;

class EnrollmentController extends Controller
{
    public function store(CreateRequest $request, CreateEnrollmentUseCase $useCase)
    {
        try {
            $useCase->execute($request->toCommand());

            return back()->with('success', '履修登録しました');
        } catch (UserNotFoundException) {
            return back()->with('error', 'ログインしてください。');
        } catch (StudentNotFoundException) {
            return back()->with('error', '学生情報が見つかりません。');
        } catch (EnrollmentAlreadyExistsException) {
            return back()->with('error', 'すでに履修登録されています。');
        }
    }

    public function drop(DropRequest $request, DropEnrollmentUseCase $useCase)
    {
        try {
            $useCase->execute($request->toCommand());

            return back()->with('success', '履修登録を取り消しました');
        } catch (UserNotFoundException) {
            return back()->with('error', 'ログインしてください。');
        } catch (StudentNotFoundException) {
            return back()->with('error', '学生情報が見つかりません。');
        } catch (EnrollmentNotFoundException) {
            return back()->with('error', '履修登録が見つかりません。');
        }
    }
}
