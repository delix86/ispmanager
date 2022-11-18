<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {

    Route::get('/', function () {
        return view('welcome');
    })->middleware('guest');

    Route::get('/tasks', 'TaskController@index');
    Route::get('/tasks/{task}/show', 'TaskController@show');
    Route::get('/tasks/add', 'TaskController@add');

    Route::post('/task', 'TaskController@store');
    Route::post('/tasks/{task}/edit', 'TaskController@edit');
    Route::post('/tasks/{task}/close', 'TaskController@close');
    Route::post('/tasks/{task}/changestate', 'TaskController@changestate');
    Route::patch('/tasks/{task}', 'TaskController@update');

    Route::delete('/task/{task}', 'TaskController@destroy');

    Route::get('/news', 'NewsController@index');

    Route::post('/tasks/{task}/notes', 'NoteController@store');
    Route::post('/notes/{note}/edit', 'NoteController@edit');
    Route::patch('/notes/{note}', 'NoteController@update');

    Route::auth();

    //SMS
    Route::get('/sms', 'SmsController@index');
    Route::post('/sms/add', 'SmsController@add');
    Route::get('/sms/{task}/show', 'SmsController@show');
    Route::get('/sms/add', 'SmsController@add');
    Route::post('/sms/sendlogin', 'SmsController@sendlogin');
    

});
