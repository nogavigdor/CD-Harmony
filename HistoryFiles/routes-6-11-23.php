<?php

require_once './config/constants.php';
require_once './router.php';

get('/CDshop', function () {
    include 'views/MainView.php';
});


get('/product/(\d+)', function ($id) {
    $controller = new controllers\ProductController();
    $controller->showProductDetails($id);
});

var_dump("Routes Defined in routes.php:"); // Debugging
var_dump($routes); // Debugging
