<?php

if (!function_exists('currentUserLogin'))
{
    function currentUserLogin ()
    {
        return App\Helpers\UserHelper::currentUserLogin();
    }
}