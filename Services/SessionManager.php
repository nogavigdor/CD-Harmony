<?php
namespace Services;

class SessionManager
{   
    //starts a session
    public function startSession()
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

    //is variable set
    public static function isVar($key)
    {
        return isset($_SESSION[$key]);
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


    public static function getSessionId()
    {
        return session_id();
    }

    // generates a random string of 32 characters which is used as a CSRF token
    public static function generateCSRFToken() {
        $csrfToken = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = [
            'value' => $csrfToken,
            // Set expiration time to 30 minutes (adjust as needed)
            'expiration_time' => time() + 1800, 
        ];
        return $csrfToken;
    }
    // returns the CSRF token
    public static function getCSRFToken() {
        // CSRF tocken assigned if exists or null if not
        $csrfTokenData = $_SESSION['csrf_token'] ?? null;
        //
        if ($csrfTokenData && time() < $csrfTokenData['expiration_time']) {
            return $csrfTokenData['value'];
        }

        // if the the token expired, generate a new one
        return self::generateCSRFToken();
    }
    // validates the CSRF token
    public static function validateCSRFToken($token) {
        $csrfTokenData = SessionManager::getSessionVariable('csrf_token');
    
        // Check if the token exists and is valid (value matched and not expired)
        if ($csrfTokenData && $token === $csrfTokenData['value'] && time() < $csrfTokenData['expiration_time']) {
            return true; // Token is valid
        } else{
         // Token doesn't match
        
        return false; // Validation failed
         }
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