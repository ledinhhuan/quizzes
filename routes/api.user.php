<?php

Route::post('signup', 'UserController@signUp');
Route::post('login', 'UserController@login');

Route::group(['middleware' => ['token.user']], function () {
    Route::get('history-tests', 'TestController@historyResults');
    Route::get('tests/{id}', 'TestController@showQuizz');
    Route::get('results/{id}', 'TestController@showResult');
    Route::get('rank-of-quizzes', 'TestController@rankOfQuizzes');
    Route::post('tests', 'TestController@doQuizz');
    Route::delete('delete-result/{id}', 'TestController@deleteResult');
});
