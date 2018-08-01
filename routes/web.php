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

Route::get('/settings', 'HomeController@index')->name('user.settings');

Route::namespace('Admin')->group(function () {
    Route::get('/admin/sensors', 'SensorController@index')->name('admin.sensors');
    Route::get('/admin/sensor/{uuid}', 'SensorController@sensor')->name('admin.sensor');
    Route::get('/admin/invites', 'UserController@invites')->name('admin.invites');
    Route::any('/admin/invites/add', 'UserController@addInvites')->name('admin.invites.add');
    Route::any('/admin/invites/delete/{id}', 'UserController@deleteInvite')->name('admin.invites.delete');
});
