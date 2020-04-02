<?php

use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

Route::prefix('v2')->group(function () {
    Route::post('login', 'Api\AuthController@login');
    Route::post('register', 'Api\AuthController@register');
});

Route::prefix('v2')->middleware(['auth:api', 'role'])->group(function() {


    // List users
    Route::middleware(['scope:admin'])->get('/users', 'Api\UserController@index');
    Route::middleware(['scope:admin'])->get('/user/{id}', 'Api\UserController@show');
    Route::middleware(['scope:admin,auteur,joueur'])->get('personnes', 'Api\PersonneController@index');
    Route::middleware(['scope:admin,auteur,joueur'])->get('personnes/{id}', 'Api\PersonneController@show');
    Route::get('getPersonne', 'Api\PersonneController@getPersonne');

    // Add/Edit User
    Route::middleware(['scope:admin,auteur'])->post('/user', 'Api\UserController@create');
    Route::middleware(['scope:admin,auteur'])->put('/user/{userId}', 'Api\UserController@update');
    Route::middleware(['scope:admin,auteur'])->put('personnes/{id}', 'Api\PersonneController@update');
    Route::middleware(['scope:admin,auteur'])->put('personnes', 'Api\PersonneController@update');

    // Delete User
    Route::middleware(['scope:admin'])->delete('/user/{userId}', 'Api\UserController@delete');
    Route::middleware(['scope:admin'])->delete('personnes/{id}', 'Api\PersonneController@destroy');

    // List statistiques
    Route::middleware(['scope:admin,auteur,joueur'])->get('statistiques', 'Api\StatController@index');
    Route::middleware(['scope:admin,auteur,joueur'])->get('statistiques/{id}', 'Api\StatController@show');
    Route::get('getStatistique', 'Api\StatController@getStatistique');

    // Add/Edit Statistique
    Route::middleware(['scope:admin,auteur'])->put('/statistique/{userId}', 'Api\StatController@update');
    Route::middleware(['scope:admin,auteur'])->put('statistiques/{id}', 'Api\StatController@update');
    Route::middleware(['scope:admin,auteur'])->put('statistiques', 'Api\StatController@update');

    Route::post('logout', 'Api\AuthController@logout');

    Route::get('getUser', 'Api\AuthController@getUser');



});
