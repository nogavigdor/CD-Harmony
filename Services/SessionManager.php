<?php
namespace Services;

class SessionManager
{   
    //starts a session
    public static function startSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    //sets a session variable
    public static function setSessionVariable($key, $value)
    {
     
        $_SESSION[$key] = $value;
    }
    //returns a session variable
    public static function getSessionVariable($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }
    //removes a session variable
    public static function unsetSessionVariable($key)
    {
   
    if (isset($_SESSION[$key])) {
        unset($_SESSION[$key]);
    }
    }
    //returns true if the user is logged in
    public static function isLoggedIn()
    {
        if (!isset($_SESSION['user']['logged_in'])) {
            return false;
        }
        return self::getSessionVariable('user')['logged_in'];
    }
    //returns the user id of the logged in user
    public static function getLoggedInUserId()
    {
        return self::getSessionVariable(['user']['id']);
    }

    // generates a random string of 32 characters which is used as a CSRF token
    public static function generateCSRFToken() {
        $csrfToken = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrfToken;
        return $csrfToken;
    }
    // returns the CSRF token
    public static function getCSRFToken() {
        return $_SESSION['csrf_token'] ?? null;
    }
    // validates the CSRF token
    public static function validateCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && $token === $_SESSION['csrf_token'];
    }

    
    //Destroys the current session
    public static function destroySession()
    {
        session_destroy();
    }

    
    // Clears all session variables
    public static function clearAll()
    {
        session_unset();
        session_destroy();
    }
}