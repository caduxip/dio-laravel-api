<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/hello/{name}', function ($name) {
    return 'Hello, World! ' . $name;
});

Route::post('/hello-post/{name}', 'HelloWorldController@hello');

Route::get('/health', 'SystemController@health');
Route::get('/stats', 'SystemController@stats');
Route::get('/search', 'SearchController@index');

Route::prefix('bands')->group(function () {
    Route::get('/', 'BandController@index');
    Route::get('/{id}/albums', 'BandController@albums');
    Route::get('/{id}/shows', 'BandController@shows');
    Route::get('/{id}', 'BandController@show');
    Route::post('/', 'BandController@store');
    Route::put('/{id}', 'BandController@update');
    Route::delete('/{id}', 'BandController@destroy');
});

Route::prefix('albums')->group(function () {
    Route::get('/', 'AlbumController@index');
    Route::get('/{id}/tracks', 'AlbumController@tracks');
    Route::post('/{id}/tracks', 'AlbumController@storeTrack');
    Route::get('/{id}', 'AlbumController@show');
    Route::post('/', 'AlbumController@store');
    Route::put('/{id}', 'AlbumController@update');
    Route::delete('/{id}', 'AlbumController@destroy');
});

Route::prefix('shows')->group(function () {
    Route::get('/', 'ShowController@index');
    Route::get('/{id}', 'ShowController@show');
    Route::post('/', 'ShowController@store');
    Route::put('/{id}', 'ShowController@update');
    Route::delete('/{id}', 'ShowController@destroy');
});

Route::prefix('movies')->group(function () {
    Route::get('/', 'MovieController@index');
    Route::get('/top-rated', 'MovieController@topRated');
    Route::get('/director/{name}', 'MovieController@byDirector');
    Route::get('/genre/{genre}', 'MovieController@byGenre');
    Route::get('/year/{year}', 'MovieController@byYear');
    Route::get('/search/{term}', 'MovieController@search');
    Route::get('/{id}/cast', 'MovieController@cast');
    Route::get('/{id}/similar', 'MovieController@similar');
    Route::get('/{id}/reviews', 'MovieController@reviews');
    Route::post('/{id}/reviews', 'MovieController@storeReview');
    Route::get('/{id}', 'MovieController@show');
    Route::post('/', 'MovieController@store');
    Route::put('/{id}', 'MovieController@update');
    Route::delete('/{id}', 'MovieController@destroy');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
