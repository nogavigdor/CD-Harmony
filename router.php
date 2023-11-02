<?php
$routes = [];

function route($action, Closure $callback)
{
    global $routes;
    $action = trim($action, '/');
    $routes[$action] = $callback;
}

// Define your routes
route('/CDshop/', function () {
    require "views/MainView.php";
});

route('/CDshop/product_details/', function () {
    require "views/ProductView.php";
});


function dispatch($action)
{
    global $routes;

    if (array_key_exists($action, $routes)) {
        echo call_user_func($routes[$action]);
    } else {
        // Handle 404 or a default action
        echo "404 - Not Found";
    }
}
?>
