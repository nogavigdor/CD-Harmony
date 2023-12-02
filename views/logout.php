<?php
session_start();

// Clear specific session variables
unset($_SESSION['user_id']);
unset($_SESSION['username']);
// ... unset other variables

// Destroy the entire session
session_destroy();

// Redirect to the login page
header("Location:". BASE_URL  ."/login");
exit();
