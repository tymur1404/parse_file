<?php

namespace App\Services;

use App\Http\Filters\UserFilter;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ParseFile
{
    public static function readFile($filename, $delimiter=','): \Generator
    {
        $file = fopen($filename, 'r');
        if (!$file) {
            die('Could not open file');
        }

        $header = null;
        while (($row = fgetcsv($file, 0, $delimiter)) !== false) {
            if (!$header) {
                $header = $row;
            } else {
                yield array_combine($header, $row);
            }
        }

        fclose($file);

    }

    public static function importUsers($filename, $delimiter=','): void
    {
        $file = fopen($filename, 'r');
        if (!$file) {
            die('Could not open file');
        }

        $users = [];

        DB::beginTransaction();

        try {
            foreach (self::readFile($filename, $delimiter) as $row) {
                $category = Category::firstOrCreate([
                    'name' => $row['category'],
                ]);

                $users[] = [
                    'email' => $row['email'],
                    'category_id' => $category->id,
                    'firstname' => $row['firstname'],
                    'lastname' => $row['lastname'],
                    'gender' => $row['gender'],
                    'birthdate' => $row['birthDate'],
                ];


                if (count($users) % 500 == 0) {
                    DB::table('users')->insertOrIgnore($users);
                    $users = [];
                }
            }

            // Записываем оставшиеся записи
            if (count($users) > 0) {
                DB::table('users')->insertOrIgnore($users);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        fclose($file);

    }

    public static function exportUsers(UserFilter $filter): StreamedResponse
    {
        $fileName = 'users.csv';
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => sprintf('attachment; filename="%s"', $fileName),
        ];

        $users = User::filter($filter)->cursor();

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');

            // Записываем заголовки CSV-файла
            fputcsv($file, ['ID', 'First Name', 'Last Name', 'Gender', 'Email', 'Favorite Category', 'Birthdate']);

            // Итерируем по записям и записываем их в файл
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->firstname,
                    $user->lastname,
                    $user->gender,
                    $user->email,
                    $user->category->name,
                    $user->birthdate,
                ]);
            }

            fclose($file);
        };
        return new StreamedResponse($callback, 200, $headers);
    }
}
