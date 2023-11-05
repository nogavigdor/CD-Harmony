<?php
define("BASE_URL", "http://localhost/CDshop");

require_once "router.php";

$action = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$action = trim($action, '/');

dispatch($action);
?>
