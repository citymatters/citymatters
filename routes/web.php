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
    return view('index');
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/admin/sensors', 'SensorController@adminIndex')->name('admin.sensors');
Route::get('/admin/invites', 'UserController@adminInvites')->name('admin.invites');
Route::any('/admin/invites/add', 'UserController@adminAddInvites')->name('admin.invites.add');
