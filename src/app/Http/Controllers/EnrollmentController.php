<?php

namespace App\Http\Controllers;

use App\Application\Enrollment\CreateEnrollmentCommand;
use App\Application\Enrollment\CreateEnrollmentUseCase;
use App\Domain\Enrollment\Exceptions\EnrollmentAlreadyExistsException;
use App\Domain\Student\Exceptions\StudentNotFoundException;
use App\Domain\User\Exceptions\UserNotFoundException;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function store(Request $request, CreateEnrollmentUseCase $useCase)
    {
        try {
            $courseOfferingId = (int) $request->route('courseOffering');

            $command = new CreateEnrollmentCommand(
                courseOfferingId: $courseOfferingId,
            );

            $useCase->execute($command);

            return back()->with('success', '履修登録しました');
        } catch (UserNotFoundException) {
            return back()->with('error', 'ログインしてください。');
        } catch (StudentNotFoundException) {
            return back()->with('error', '学生情報が見つかりません。');
        } catch (EnrollmentAlreadyExistsException) {
            return back()->with('error', 'すでに履修登録されています。');
        }
    }
}
