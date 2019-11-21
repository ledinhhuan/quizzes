<?php

namespace App\Helpers;

use Tymon\JWTAuth\JWTAuth;

class UserHelper
{
    private $cacheUser = null;
    private static $instance;

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new UserHelper();
        }

        return self::$instance;
    }

    public function parserToken()
    {
        if ($this->cacheUser === null) {
            try {
                $this->cacheUser = JWTAuth::parseToken()->authenticate();
            } catch (\Exception $e) {
                $this->cacheUser = false;
            }
        }

        return $this->cacheUser;
    }

    public static function currentUserLogin()
    {
        return self::getInstance()->parserToken();
    }
}