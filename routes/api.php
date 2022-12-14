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

Route::controller('App\Http\Controllers\Api\V1\RegisterController')->group(function(){
    Route::post('/v1/user/register', 'register');
    Route::post('/v1/user/login', 'login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('/v1/quote', 'App\Http\Controllers\Api\V1\QuoteController')->except(['create', 'edit', 'show']);
});
