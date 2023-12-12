<?php
use Services\SessionManager;
session_start();
// Unset all of the session variables
SessionManager::logout();

// Redirect to the login page
header("Location:". BASE_URL  ."/login");
exit();
