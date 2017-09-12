<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
// Home and Update Password Routes
Route::get('/', 'PagesController@index');
Route::get('/update-password', 'PagesController@showUpdatePasswordForm');
Route::put('/update-password', 'PagesController@updatePassword');

// Clients Routes
Route::resource('/clients', 'ClientsController');

Route::resource('/buckets', 'BucketsController');
Route::resource('/buckets', 'BucketsController');

// Invoice Routes
Route::resource('/clients.invoices', 'InvoicesController', ['except' => [
    'update', 'edit'
]]);

// Project Routes
Route::resource('/clients.projects', 'ProjectsController', ['except' => [
    'update', 'edit'
]]);
// Admin Routes
Route::resource('/admins', 'AdminsController', ['except' => [
    'show'
]]);

// Authentication Routes
Auth::routes();

Route::get('/logout', 'Auth\LoginController@logout');
Route::get('/add-target', 'UsersController@target');
Route::get('/get-table', 'UsersController@getTable');
Route::get('/view-table', 'UsersController@table');
Route::get('/user-add', 'UseraddController@aduserTemplate');
Route::any('/user-insert', 'UseraddController@aduserInsert');
Route::get('/userprofile', 'UseraddController@userprofileUpdate');
