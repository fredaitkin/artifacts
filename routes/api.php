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

Route::group(['middleware' => 'artifacts.auth'], function() {
    Route::get('player', 'PlayerAPIController@getPlayerCount');
    Route::get('player/state', 'PlayerAPIController@getStateCount');
    Route::get('player/country', 'PlayerAPIController@getCountryCount');
    Route::get('player/us', 'PlayerAPIController@getUSPlayerCount');
    Route::get('player/non-us', 'PlayerAPIController@getNonUSPlayerCount');
});
