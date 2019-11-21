<?php

Route::group(['middleware' => ['token.admin', 'token.user']], function () {
    Route::resource('topics', 'TopicController');
    Route::resource('questions', 'QuestionController');
});