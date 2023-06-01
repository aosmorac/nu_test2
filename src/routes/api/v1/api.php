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

// Auth
Route::prefix('/auth')->group(function() {
    Route::post('/register', 'App\Http\Controllers\api\v1\AuthController@register');
    Route::post('/register/verify/{user_id}', 'App\Http\Controllers\api\v1\AuthController@verifyNewUser');
    Route::post('/login', 'App\Http\Controllers\api\v1\AuthController@getLoginToken');
    Route::post('/login/verify', 'App\Http\Controllers\api\v1\AuthController@getAccessToken')->middleware(['auth:api', 'scopes:user:verify-login']);
});


Route::prefix('/vin')->group(function() {
    Route::get('/search/{vin}', 'App\Http\Controllers\api\v1\VinController@search')->middleware(['auth:api', 'scopes:user:all']);
});
