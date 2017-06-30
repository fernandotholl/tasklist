<?php

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
    return redirect('login');
});

// Basic API Auth
Route::post('/api/authenticate', 'AuthController@authenticate');
Route::group(['prefix' => 'api',  'middleware' => 'jwt.auth'], function()
{
	Route::post('/tasks/order', 'TasksController@order');
	Route::post('/tasks/complete/{id}', 'TasksController@complete');
    Route::resource('tasks', 'TasksController');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
