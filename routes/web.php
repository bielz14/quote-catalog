<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;

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

Route::get('/', 'App\Http\Controllers\QuoteController@index')->name('quote.list');
Route::resource('/quote', 'App\Http\Controllers\QuoteController')->except(['index', 'show']);
/*Route::get('/quote/create', 'App\Http\Controllers\QuoteController@create')->name('form_create');
Route::get('/quote/create/{quoteId}', 'App\Http\Controllers\QuoteController@create')->name('form_update');
Route::post('/quote/store', 'App\Http\Controllers\QuoteController@store')->name('store');
Route::post('/quote/update/{quote_id}', 'App\Http\Controllers\QuoteController@update')->name('update');
Route::post('/quote/delete/{quote_id}', 'App\Http\Controllers\QuoteController@delete')->name('delete');*/
