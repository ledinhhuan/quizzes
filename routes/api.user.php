<?php

Route::post('signup', 'UserController@signUp');
Route::post('login', 'UserController@login');

Route::group(['middleware' => ['token.user']], function () {
});