<?php
require_once './bootstrap.php';
require_once './routes.php';

$action = trim($_SERVER['REQUEST_URI'], '/');
$method = $_SERVER['REQUEST_METHOD'];

var_dump("Action in index.php: $action"); // Debugging
var_dump("Method in index.php: $method"); // Debugging

dispatch($action, $method);
