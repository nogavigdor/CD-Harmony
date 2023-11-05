<?php

include_once 'controllers/DefaultController.php';
include_once 'controllers/ProductController.php';
include_once 'models/product_details.php';

$action = isset($_GET['a']) ? $_GET['a'] : 'index';
$module = isset ($_GET['m']) ? $_GET['m'] : '';

switch ($module) {
    case 'product_details':
        $controller = new ProductController();
        break;
    default:
        $controller = new DefaultController();
}

$controller->run($action);