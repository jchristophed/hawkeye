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

Route::get('/', ['as' => 'home', 'uses' => 'ResidenceController@index']);
Route::get('/login', ['as' => 'auth.login', 'uses' => 'Auth\AuthController@login']);
Route::get('/logout', ['as' => 'auth.logout', 'uses' => 'Auth\AuthController@logout']);
Route::get('/residence/{residence}/', ['as' => 'residence.index', 'uses' => 'DashboardController@index'])->where(['residence' => '[0-9]+']);;
Route::get('/social/redirect/{provider}',   ['as' => 'social.redirect', 'uses' => 'Auth\AuthController@getSocialRedirect'])->where(['provider' => '[a-z]+']);;
Route::get('/social/handle/{provider}',     ['as' => 'social.handle', 'uses' => 'Auth\AuthController@getSocialHandle'])->where(['provider' => '[a-z]+']);;

Route::resource('residence.flat', 'FlatController');
Route::resource('residence.tenant', 'TenantController');
Route::resource('residence.contract', 'ContractController');