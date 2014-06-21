<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::pattern('pseudo', '\w+');
Route::pattern('id', '\d+');

Route::group([
    'prefix' => 'api',
    'before' => 'basic',
], function(){
    Route::get('/animelist',[
        'uses'   => 'AnimeController@index',
        'as'     => 'Anime.index',
    ]);

    Route::get('/animelist/{pseudo}',[
        'uses' => 'AnimeController@getList',
        'as'   => 'anime.list',
    ]);

    Route::get('/anime/{id}',[
        'uses'   => 'AnimeController@get',
        'as'     => 'Anime.get',
    ]);

});
