<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', 'WelcomeController@index');

Route::auth();

Route::get('/home', 'HomeController@index');

// 추가 부분
Route::get('auth/github', 'Auth\AuthController@redirectToGithub');
Route::get('auth/github/callback', 'Auth\AuthController@handleGithubCallback');

Route::group(['middleware' => 'auth'], function () {
    Route::resource('project', 'ProjectController');
    Route::resource('project.task', 'ProjectTaskController');
    Route::resource('task', 'TaskController', ['only' => [
        'index', 'show',
    ]]);
});

Route::get('/reminder/{userid}/{dueInDays?}', 'ReminderController@sendEmailReminder');