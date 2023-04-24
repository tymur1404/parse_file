<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Route::controller(\App\Http\Controllers\UserController::class)->group(function () {
    Route::get('/', 'index')->name('users.index');
    Route::post('/import', 'import')->name('users.import');
    Route::get('/export', 'export')->name('users.export');
});


