<?php

Route::group(['middleware' => ['token.admin', 'token.user']], function () {
});