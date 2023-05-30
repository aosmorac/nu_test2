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

Route::prefix('/contact')->group(function() {
    Route::post('', 'App\Http\Controllers\api\v1\ContactController@store');
});

// Auth
Route::prefix('/auth')->group(function() {
    Route::post('/register', 'App\Http\Controllers\api\v1\AuthController@register');
});
