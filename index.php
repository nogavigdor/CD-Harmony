<?php 
define("BASE_URL", "http://localhost/CDshop");

require_once "router.php";
require_once "bootstrap.php";

route('/CDshop/', 'GET', function () {
    require "views/MainView.php";
});

route('/CDshop/product/(\d+)', 'GET', function ($id) {
    $controller = new controllers\ProductController();
    $controller->showProductDetails($id);
});

route('/CDshop/contact/', 'GET', function() {
    $controller = new controllers\ContactController();
    $controller->handleInput();
});

$action = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'); // Extract the path
$method = $_SERVER['REQUEST_METHOD'];
dispatch($action, $method);
