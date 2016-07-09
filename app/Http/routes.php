<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::resource('/', 'Auth\AuthController@index');
Route::resource('/residence/', 'ResidenceController@index');
Route::get('/residence/{residence}/', ['middleware' => 'auth', 'as' => 'residence.index', 'uses' => 'DashboardController@index']);
Route::resource('residence.flat', 'FlatController');
Route::resource('residence.tenant', 'TenantController');
Route::resource('residence.contract', 'ContractController');

Route::get('/social/redirect/{provider}',   ['as' => 'social.redirect', 'uses' => 'Auth\AuthController@getSocialRedirect']);
Route::get('/social/handle/{provider}',     ['as' => 'social.handle', 'uses' => 'Auth\AuthController@getSocialHandle']);