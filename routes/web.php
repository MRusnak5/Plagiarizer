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



Route::get('logmeout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logmeout');
Auth::routes(['register' => false]);
Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::view('about', 'about')->name('about');

    Route::get('users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::get('course_analyze/{id}', [\App\Http\Controllers\CourseController::class,'analyze'])->name('course.analyze');
    Route::resource('courses', \App\Http\Controllers\CourseController::class);
    Route::get('getAttempts', [\App\Http\Controllers\CourseController::class, 'getAttempts'])->name('getAttempts');
    Route::get('getAttempts2', [\App\Http\Controllers\CourseController::class, 'getAttempts2'])->name('getAttempts2');
    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});
