<?php
namespace Services;

class SessionManager
{
    public static function startSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function setSessionVariable($key, $value)
    {
     
        $_SESSION[$key] = $value;
    }

    public static function getSessionVariable($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public static function unsetSessionVariable($key)
    {
   
    if (isset($_SESSION[$key])) {
        unset($_SESSION[$key]);
    }
    }


    public static function destroySession()
    {
        session_destroy();
    }
}