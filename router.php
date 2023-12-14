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
 * @param $path string
 * @param \Closure $callback Called when the current URL matches the provided action
 */
function route($path, $method, Closure $callback)
{
    global $routes;
    $path = trim($path, '/');
    $path = str_replace('/', '\/', $path); // Escape slashes
    $path = '/^' . $path . '$/'; // Use of ^ and $ to match the whole URL

    $routes[$path][$method] = $callback;
}

/**
 * Dispatch the router
 *
 * @param $path string
 */
function dispatch($path, $method)
{
    global $routes;
    $path = trim($path, '/');
    
    foreach ($routes as $route => $callbacks) {
        if (preg_match($route, $path, $matches) && isset($callbacks[$method])) {
            array_shift($matches); // Remove the full match from the array
            //passes the matched value into the callback
            echo call_user_func_array($callbacks[$method], $matches);
            return;
        }
    }

    // Handle 404 or redirect to an error page
    include 'views/404.php';
}
