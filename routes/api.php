<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::group(['namespace' => '\App\Http\Controllers\Api'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/login', 'AuthController@login');
        Route::post('/register', 'AuthController@register');
    });
    Route::group(['prefix' => 'nyadran'], function () {
        Route::get('/statistik', 'NyadranController@stats');
        Route::get('/all', 'NyadranController@index');
        Route::get('/export', 'NyadranController@export');
        Route::get('/show/{id}', 'NyadranController@show');
        Route::post('/search', 'NyadranController@search');
        Route::get('/address', 'NyadranController@addresses');
        Route::get('/name/arwahs', 'NyadranController@arwahs');
        Route::group(['middleware' => ['auth:sanctum']], function () {
            Route::group(['middleware' => ['role:Admin|Super Admin|Officer']], function () {
                Route::post('/store', 'NyadranController@store');
                Route::post('/arwah/{sender}/new', 'NyadranController@addArwah');
                Route::delete('/arwah/{arwah}/delete', 'NyadranController@destroyArwah');
                Route::put('/arwah/{arwah}/edit', 'NyadranController@editArwah');
                Route::put('/sender/{sender}/edit', 'NyadranController@editSender');
                Route::delete('/sender/{sender}/delete', 'NyadranController@destroySender');
            });
        });
    });
    Route::group(['prefix' => 'admin'], function () {
        Route::group(['middleware' => ['auth:sanctum']], function () {
            Route::group(['middleware' => ['role:Admin|Super Admin']], function () {
                Route::get('/logs','NyadranController@logIndex');
                Route::get('/roles','UserController@roles');
                Route::get('/users','UserController@index');
                Route::post('/users/store', 'UserController@createUser');
                Route::delete('/users/{id}/delete', 'UserController@deleteUser');
            });
        });
    });
    
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return [
        "user" => $request->user(),
        "roles" => $request->user()->roles
    ];
});
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
