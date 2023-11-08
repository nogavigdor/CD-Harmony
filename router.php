<?php
/**
 * Holds the registered routes
 *
 * @var array $routes
 */
$routes = [];

/**
 * Register a new route
 *
 * @param $action string
 * @param \Closure $callback Called when the current URL matches the provided action
 */
function route($action, $method, Closure $callback)
{
    global $routes;
    $action = trim($action, '/');
    $action = str_replace('/', '\/', $action); // Escape slashes
    $action = '/^' . $action . '$/'; // Use ^ and $ to match the whole URL

    $routes[$action][$method] = $callback;
}

/**
 * Dispatch the router
 *
 * @param $action string
 */
function dispatch($action, $method)
{
    global $routes;
    $action = trim($action, '/');
    
    foreach ($routes as $route => $callbacks) {
        if (preg_match($route, $action, $matches) && isset($callbacks[$method])) {
            array_shift($matches); // Remove the full match from the array
            //passes the matched value into the callback
            echo call_user_func_array($callbacks[$method], $matches);
            return;
        }
    }

    // Handle 404 or redirect to an error page
    include 'views/404.php';
}
