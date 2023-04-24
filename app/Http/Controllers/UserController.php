<?php

namespace App\Http\Controllers;

use App\Http\Filters\UserFilter;
use App\Http\Requests\User\ImportRequest;
use App\Http\Requests\User\FilterRequest;
use App\Models\Category;
use App\Models\User;
use App\Services\ParseFile;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserController extends Controller
{

    public function index(FilterRequest $request)
    {


        $data = $request->validated();
        $filter = app()->make(UserFilter::class, ['queryParams' => array_filter($data)]);
        $users = User::filter($filter)->paginate(20);

        //get parameters for filter
        $categories = Category::all();
        $genders = [User::GENDER_MALE, User::GENDER_FEMALE];
        $minMaxAge = [
            'minAge' => Carbon::parse(User::max('birthdate'))->age,
            'maxAge' => Carbon::parse(User::min('birthdate'))->age,
        ];

        return view('users.index', compact('users','categories', 'genders', 'minMaxAge'));
    }


    public function import(ImportRequest $request)
    {
        $data = $request->validated();
        $file = $request->file('file');

        if (!$file) {
            return redirect()->back()->withErrors(['error' => 'File not uploaded']);
        }

        $filepath = $file->getRealPath();

        ParseFile::importUsers($filepath);

        return redirect()->back()->with('success', 'File uploaded successfully');

    }

    public function export(FilterRequest $request)
    {
        $data = $request->validated();
        $filter = app()->make(UserFilter::class, ['queryParams' => array_filter($data)]);

        return ParseFile::exportUsers($filter);

    }
}
