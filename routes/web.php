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

use Illuminate\Http\Request;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/players', 'PlayerController@index');

    Route::any('/players/search', 'PlayerController@search');

    Route::get('/player', 'PlayerController@create');

    Route::post('/player', 'PlayerController@store');

    Route::get('/player/{id}', 'PlayerController@edit');

    Route::delete('/player/{id}', 'PlayerController@destroy');

    Route::get('/demographics', 'DemographicsController@index');

    Route::get('/statistics', 'StatisticsController@index');

});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');