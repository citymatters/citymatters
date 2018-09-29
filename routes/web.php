<?php

/*
 * Copyright (C) 2018 city_matters. All rights reserved.
 */

Route::get('/', function () {
    return view('index');
})->name('idx');
Auth::routes();

Route::get('/diy', function () {
    return view('diy_hardware');
})->name('diy');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/settings', 'HomeController@index')->name('user.settings');

Route::group(['middleware' => 'admin', 'namespace' => 'Admin'], function () {
    Route::get('/dashboard', 'HomeController@index')->name('admin.dashboard');
    Route::get('/admin/sensors', 'SensorController@index')->name('admin.sensors');
    Route::get('/admin/sensor/{uuid}', 'SensorController@sensor')->name('admin.sensor');

    Route::get('/admin/users/{id}', 'UserController@user')->name('admin.user');
    Route::get('/admin/users', 'UserController@users')->name('admin.users');
    Route::any('/admin/user/modify', 'UserController@modifyUser')->name('admin.users.add');

    Route::get('/admin/organization/{id}', 'UserController@organization')->name('admin.organization');
    Route::get('/admin/organizations', 'UserController@organizations')->name('admin.organizations');
    Route::any('/admin/organizations/add', 'UserController@addOrganizations')->name('admin.organizations.add');

    Route::get('/admin/invites', 'UserController@invites')->name('admin.invites');
    Route::any('/admin/invites/add', 'UserController@addInvites')->name('admin.invites.add');
    Route::any('/admin/invites/delete/{id}', 'UserController@deleteInvite')->name('admin.invites.delete');
});
