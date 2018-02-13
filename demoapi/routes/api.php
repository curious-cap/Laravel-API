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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'api2', 'middleware' => ['ability:admin']], function()
{
    Route::get('users', 'JwtAuthenticateController@index');
});
Route::post('auth', 'JwtAuthenticateController@authenticate');
Route::post('register', 'JwtAuthenticateController@register');
Route::post('role', 'JwtAuthenticateController@createRole');
Route::post('permission', 'JwtAuthenticateController@createPermission');
Route::post('assign-role', 'JwtAuthenticateController@assignRole');
Route::post('attach-permission', 'JwtAuthenticateController@attachPermission');
//Route::get('api',['middleware' => 'ability:admin','uses'=>'JwtAuthenticateController@index']);
Route::group(['prefix'=>'admin'],function(){
    Route::resource('users','JwtAuthenticateController');
});