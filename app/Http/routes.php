<?php

/**
* The home page
**/

Route::get('/', 'PagesController@home');


/**
* Notices
**/

Route::resource('notices', 'NoticesController');


/**
* Autentication
**/

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
