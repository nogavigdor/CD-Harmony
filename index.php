<?php define("BASE_URL", "http://localhost/CDshop");

require_once "router.php";

route('/CDshop/', function () {
    require "views/MainView.php";
});

$action = $_SERVER['REQUEST_URI'];
//echo $action;

dispatch($action);
