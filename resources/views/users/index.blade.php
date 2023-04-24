@extends('layouts.app')

@section('content')
@php
// Get old value from filter after filtering
    $ageOld = request()->get('age') ?? null;
    $ageRangeOld = request()->get('age_range') ?? null;
    $birthdateOld = request()->get('birthdate') ?? null;
    $genderOld = request()->get('gender') ?? null;
    $categoryOld = request()->get('category') ?? null;

    if ($ageRangeOld && strpos($ageRangeOld, ',') !== false) {
        list($ageMinOld, $ageMaxOld) = explode(',', $ageRangeOld);
    } else {
        $ageMinOld = $ageMaxOld = null;
    }
@endphp
<div class="container">
        <div class="row">
            <h3>Import</h3>
            <form method="POST" action="{{ route('users.import') }}" enctype="multipart/form-data">

            @csrf
                <div class="form-group">

                    <input type="file" class="form-control-file" name="file">

                    <button  class="btn btn-primary" type="submit">Download</button>

                    @error('file')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                </div>
            </form>
        </div>
    <hr>
        <form method="GET" action="{{ route('users.index') }}" id="filter">
            <div class="row">


                <div class="col-md-4 mb-3">
                    <label for="category">Category:</label>
                    <select class="form-control" id="category" name="category">
                        <option value="0" selected>Choose Category</option>
                        @foreach($categories as $category)
                            <option {{ $categoryOld == $category->name }} value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>

                </div>
                <div class="col-md-4 mb-3">
                    <label for="gender">Category:</label>
                    <select class="form-control" id="gender" name="gender">
                        <option value="0" selected>Choose Gender</option>
                        @foreach($genders as $gender)
                            <option {{ $genderOld == $gender ?? 'checked' }}  value="{{ $gender }}">{{ $gender }}</option>
                        @endforeach
                    </select>

                </div>
                <div class="col-md-4 mb-3">
                    <label for="from_date">Date of Birth</label>
                    <input type="date" value="{{ $birthdateOld }}" class="form-control" id="from_date" name="birthdate">
                </div>

                <div id="age_simple" class="row {{ $ageRangeOld ? 'd-none' : '' }}">
                    <div class="col-sm-4">
                        <label for="age">Age</label>
                        <input type="number" value="{{ $ageOld }}" class="form-control" id="age" name="age">
                    </div>
                    <div class="col-sm-8">

                    </div>
                </div>
                <div id="age_range_div" class="row {{ $ageRangeOld ?? 'd-none'}}">
                    <div class="col-sm-4">
                        <label for="age_min">Age Min</label>
                        <input type="number" value="{{ $ageMinOld }}" {{ $ageRangeOld ?? 'disabled'}}  class="form-control" id="age_min" >
                    </div>
                    <div class="col-sm-4">
                        <label for="age_max">Age Max</label>
                        <input type="number" value="{{ $ageMaxOld }}" {{ $ageRangeOld ?? 'disabled'}} class="form-control" id="age_max" >
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="disabled">Age range</label>
                    <input type="checkbox" id="disabled" >
                </div>
                <input type="hidden" id="age_range" name="age_range" value="{{ $ageRangeOld }}">

            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>

        <hr>

        <h1>Users</h1>
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">First name</th>
                <th scope="col">Last name</th>
                <th scope="col">Email</th>
                <th scope="col">Gender</th>
                <th scope="col">Favorite Category</th>
                <th scope="col">Birthdate</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <th scope="row">{{$user->id}}</th>
                    <td>{{$user->firstname}}</td>
                    <td>{{$user->lastname}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->gender}}</td>
                    <td>{{$user->category->name}}</td>
                    <td>{{$user->birthdate}}</td>
                </tr>
            @endforeach

            </tbody>
        </table>
        <div class="row">
            <div class="col-sm-6">
                {{$users->withQueryString()->links()}}
            </div>

            <div class="col-sm-6">
                <a class="btn btn-success" target="_blank" href="/export{{ request()->getRequestUri() }}">Export users</a>
            </div>
        </div>
</div>


@endsection
