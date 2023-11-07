<?php
require_once './config/constants.php';
require_once './router.php';

route(BASE_URL, function () {
    include 'views/MainView.php'; // Static GET for the home page
});

route(BASE_URL . 'product/(\d+)', function ($id) {
    $controller = new controllers\ProductController();
    $controller->showProductDetails($id);
});

// Define other routes here

dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
