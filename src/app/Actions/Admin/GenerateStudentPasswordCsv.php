<?php

namespace App\Actions\Admin;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateStudentPasswordCsv
{
    public function execute(array $accounts, string $directory): string
    {
        $filename = Str::uuid().'.csv';

        $path = $directory.'/'.$filename;

        Storage::makeDirectory($directory);

        $handle = fopen(Storage::path($path), 'w');

        fputcsv($handle, ['name', 'email', 'password']);

        foreach ($accounts as $account) {
            fputcsv($handle, [
                $account['name'],
                $account['email'],
                $account['password'],
            ]);
        }

        fclose($handle);

        return $filename;
    }
}
