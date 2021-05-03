<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::group(['middleware' => ['auth']], function() {
    Route::resource('users', UserController::class);
    Route::resource('courses', CourseController::class);
});

Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/users', 'UserController@index')->name('user');
Route::post('/adduser', 'UserController@store')->name('adduser');
Route::get('/users/approve/{id}', [App\Http\Controllers\UserController::class, 'approve'])->name('users.approve');
Route::get('/pending', [App\Http\Controllers\UserController::class, 'pending'])->name('pending');
Route::post('/users/update/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
Route::post('/courses/update/{id}', [App\Http\Controllers\CourseController::class, 'update'])->name('courses.update');

Route::post('/updateuser/{id}', 'UserController@updateUserDetails')->name('userupdate');
Route::post('/courses/create', 'CourseController@fileUploadPost')->name('fileUploadPost');

