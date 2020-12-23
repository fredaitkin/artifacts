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
    return view('welcome');
});

Route::get('home', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    Route::get('players', 'PlayerController@index')->name('players');

    Route::get('demographics', 'DemographicsController@index')->name('demographics');

    Route::get('statistics', 'StatisticsController@index')->name('statistics');

    Route::get('funfacts', 'FunFactsController@index')->name('funfacts');

});

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('player', 'PlayerController@create')->name('player.add');

    Route::post('player', 'PlayerController@store')->name('player.store');

    Route::get('player/{id}', 'PlayerController@edit')->name('player.read');

    Route::delete('player/{id}', 'PlayerController@destroy')->name('player.delete');

    Route::get('teams', 'TeamsController@index')->name('teams');

    Route::get('team/{code}', 'TeamsController@edit')->name('team');

    Route::get('team', 'TeamsController@create')->name('team.add');

    Route::post('team', 'TeamsController@store')->name('team.store');

    Route::get('minor-league-teams', 'MinorLeagueTeamsController@index')->name('minor-league-teams');

    Route::get('minor-league-team/{id}', 'MinorLeagueTeamsController@edit')->name('minor-league-team');

    Route::get('minor-league-team', 'MinorLeagueTeamsController@create')->name('minor-league-team.add');

    Route::post('minor-league-team', 'MinorLeagueTeamsController@store')->name('minor-league-team.store');

    Route::get('other-teams', 'OtherTeamsController@index')->name('other-teams');

    Route::get('other-team/{id}', 'OtherTeamsController@edit')->name('other-team');

    Route::get('other-team', 'OtherTeamsController@create')->name('other-team.add');

    Route::post('other-team', 'OtherTeamsController@store')->name('other-team.store');
    // TODO use a parameterized get
    Route::get('minor-league-teams/ajax', 'MinorLeagueTeamsAPIController@minor_league_teams')->name('minor-league-teams.ajax');
    Route::get('other-teams/ajax', 'OtherTeamsAPIController@other_teams')->name('other-teams.ajax');

});

Auth::routes();
