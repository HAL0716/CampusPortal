<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\GenerateStudentPasswordCsv;
use App\Enums\UserGrade;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    private const DOWNLOAD_DIRECTORY = 'import-results';

    public function index(Request $request): Response
    {
        return Inertia::render('Admin/Users/Index', [
            'role' => $request->query('role', UserRole::STUDENT->value),
            'grades' => UserGrade::options(),
            'departments' => Department::options(),
        ]);
    }

    public function store(
        UserStoreRequest $request,
        GenerateStudentPasswordCsv $csvAction
    ) {
        $role = $request->role();

        $generatedAccounts = [];

        DB::transaction(function () use ($request, $role, &$generatedAccounts) {

            foreach ($request->csvRows() as $row) {

                $user = User::firstOrNew([
                    'email' => $row['email'],
                ]);

                $isNew = ! $user->exists;

                $user->name = $row['name'];
                $user->role = $role;

                $user->department_id = in_array($role, [
                    UserRole::STUDENT,
                    UserRole::TEACHER,
                ], true)
                    ? $request->integer('department_id')
                    : null;

                $user->grade = $role === UserRole::STUDENT
                    ? $request->enum('grade', UserGrade::class)
                    : null;

                if ($isNew) {

                    $plainPassword = Str::password(
                        length: 12,
                        letters: true,
                        numbers: true,
                        symbols: false,
                    );

                    $user->password = Hash::make($plainPassword);

                    $generatedAccounts[] = [
                        'name' => $row['name'],
                        'email' => $row['email'],
                        'password' => $plainPassword,
                    ];
                }

                $user->save();
            }
        });

        if ($generatedAccounts === []) {
            return back()->with([
                'success' => "{$role->label()}情報を更新しました。",
            ]);
        }

        $filename = $csvAction->execute(
            $generatedAccounts,
            self::DOWNLOAD_DIRECTORY
        );

        Cache::put(
            "download:$filename",
            true,
            now()->addMinutes(30)
        );

        return back()->with([
            'success' => sprintf(
                '%d件の%sを作成しました。',
                count($generatedAccounts),
                $role->label()
            ),
            'download_url' => URL::temporarySignedRoute(
                'admin.users.export',
                now()->addMinutes(30),
                ['file' => $filename]
            ),
        ]);
    }

    public function export(string $file)
    {
        $file = basename($file);

        $path = self::DOWNLOAD_DIRECTORY . '/' . $file;

        abort_unless(
            Cache::pull("download:$file"),
            403
        );

        abort_unless(
            Storage::exists($path),
            404
        );

        return response()->download(
            Storage::path($path),
            'student-passwords.csv'
        )->deleteFileAfterSend(true);
    }
}
