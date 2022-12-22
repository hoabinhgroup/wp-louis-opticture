<?php

class Authentication {

    protected static $instance;
    const CACHE_FILE = 'authentication';
    const CACHE_NAME = 'token';


    function __construct()
    {

    }

    public static function getInstance()
    {
      if (is_null(self::$instance))
        self::$instance = new Authentication();

      return self::$instance;
    }

    public static function checkAuthentication()
    {
        $token = self::getToken();
        if(!$token){
            return Louis_Response_Handle::setResponse(
            Louis_Response_Handle::STATUS_NO_AUTH, Louis_Response_Handle::ERR_AUTHENTICATION);
        }
        return true;
    }


    public static function getToken()
    {
        $cacheAuth = new FileCache(self::CACHE_FILE);
        return $cacheAuth->isCached(self::CACHE_NAME) ? $cacheAuth->retrieve(self::CACHE_NAME): '';
    }

 }
?>
