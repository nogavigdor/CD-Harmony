<?php

$routes = [];

function get($route, $callback)
{
    global $routes;
    $routes[$route]['GET'] = $callback;
    echo "hi";
    var_dump($routes[$route]['GET']);
}

function post($route, $callback)
{
    global $routes;
    $routes[$route]['POST'] = $callback;
}

function put($route, $callback)
{
    global $routes;
    $routes[$route]['PUT'] = $callback;
}

function patch($route, $callback)
{
    global $routes;
    $routes[$route]['PATCH'] = $callback;
}

function delete($route, $callback)
{
    global $routes;
    $routes[$route]['DELETE'] = $callback;
}

function any($route, $callback)
{
    global $routes;
    $routes[$route]['ANY'] = $callback;
}

function dispatch($action, $method)
{
    global $routes;
    $action = trim($action, '/');
    var_dump("Action in dispatch function: $action"); // Debugging

    if (isset($routes[$action])) {
        $callback = $routes[$action];
        var_dump("Matched Route: $action"); // Debugging

        if (is_callable($callback)) {
            call_user_func($callback);
        } elseif (is_string($callback)) {
            if (file_exists($callback)) {
                include $callback;
            } else {
                // Handle the case when the file doesn't exist
                echo "View file not found: $callback";
            }
        } else {
            // Handle other cases or errors
            echo "Invalid callback type: " . gettype($callback);
        }
    } else {
        // Handle 404 or redirect to an error page
        var_dump("No Matching Route for Action: $action"); // Debugging
        include 'views/404.php';
    }
}


// CSRF and other functions
function out($text)
{
    echo htmlspecialchars($text);
}

function set_csrf()
{
    session_start();
    if (!isset($_SESSION["csrf"])) {
        $_SESSION["csrf"] = bin2hex(random_bytes(50));
    }
    echo '<input type="hidden" name="csrf" value="' . $_SESSION["csrf"] . '">';
}

function is_csrf_valid()
{
    session_start();
    if (!isset($_SESSION['csrf']) || !isset($_POST['csrf'])) {
        return false;
    }
    if ($_SESSION['csrf'] != $_POST['csrf']) {
        return false;
    }
    return true;
}
