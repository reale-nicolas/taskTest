<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('tasks')->group(function () 
{
    Route::get('{duedate?}/{completed?}/{update?}/{creationdate?}', 'TaskController@index');
    Route::post('',   'TaskController@create');
    Route::put('',    'TaskController@update');
    Route::delete('', 'TaskController@delete');
});